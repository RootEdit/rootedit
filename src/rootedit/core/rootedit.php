<?php

namespace rootedit\core;

use Closure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Middleware\FrameInterface;
use Psr\Http\Middleware\MiddlewareInterface;
use Psr\Http\Middleware\StackInterface;
use rootedit\core\View;
use rootedit\ioc\Container;
use rootedit\core\Input;

class RootEdit implements StackInterface, FrameInterface {

    /**
     *
     * @var ContainerInterface
     */
    private $container;
    private $commandStack = array();
    private $filterStack = array();
    public $skinurl;

    public function __construct($Container = Container::class, $in = Input::class, $out = Output::class, $view = View::class) {
        $this->events = array();

        if (is_object($Container)) {
            $this->container = $Container;
        } else {
            $this->container = new $Container();
        }

        $this->container->set('request', $in);
        $this->container->set('response', $out);
        $this->container->set('view', $view);
    }

    private function clearCommand() {
        unset($this->commandStack);
        $this->commandStack = array();
    }

    private function clearFilter() {
        $this->nb = $this->i = 0;
        unset($this->filterStack);
        $this->filterStack = array();
    }

    private function clearTemplate() {
        $this->view()->clear();
    }

    function clear() {
        $this->clearCommand();
        $this->clearTemplate();
        $this->clearFilter();
    }

    public function addRoute($urlTemplate, $route, $isGreedy=false) {
        //FNM_PATHNAME==1 >  it make it not greedy
        if (fnmatch($urlTemplate, $this->request()->getUri()->getPath(),!$isGreedy)) {
            parse_str($route, $tab);
            $this->events += $tab;
        }
    }

    public function addFilter($filter, $routeName = null, $methode = null) {
        if ((is_null($methode) || $_SERVER['REQUEST_METHOD'] == $methode ) && (isset($this->events[$routeName]) || $routeName == null)) {

            $this->filterStack[] = $filter;
        }
    }

    public function addAction($command, $routeName = null, $methode = null) {
        if ((is_null($methode) || $_SERVER['REQUEST_METHOD'] == $methode ) && (isset($this->events[$routeName]) || $routeName == null)) {
            if ($command instanceof Closure) {
                $this->commandStack[] = $command;
            } elseif ($this->container->has($command)) {
                $this->commandStack[] = $this->container->{$command};
            } else {
                $this->commandStack[] = new $command();
            }
        }
    }

    public function addTemplate($slotName, $templateUrl, $routeName = null, $methode = null) {
        if ((is_null($methode) || $_SERVER['REQUEST_METHOD'] == $methode ) && (isset($this->events[$routeName]) || $routeName == null)) {
            $this->view()->setView($slotName, $this->skinurl . $templateUrl);
        }
    }

    /*
     * PSR FrameInterface
     */

    public function next(RequestInterface $request) {
        if ($this->i-- > 0) {
            $response = $this->filterStack[$this->i]->process($request, $this);
            
            //if one midleware skip calling next() breaking the chain call,
            // or dont return a value(?'ll check if is response object?).
            if($response == null || $this->i>0){
                return $this->next($request);
            }else{
                return $response;
            }
        }
        
        foreach ($this->commandStack as $command) {
            $command(new inputHelperWarper($request), $this->view(), $this);
        }
        
        return $this->response()->withBody(new ViewStreamWrapper($this->view()));
        
    }

    /*
     * PSR StackInterface
     */

    public function process(RequestInterface $request = null) {
        $request = is_null($request) ? $this->request() : $request;
        $this->nb = $this->i = sizeof($this->filterStack);
        return $this->next($request);
    }

    public function withMiddleware(MiddlewareInterface $middleware) {
        $this->pushFilter($middleware);
        return $this;
    }

    public function withoutMiddleware(MiddlewareInterface $middleware) {

        foreach ($this->filterStack as $key => $filter) {
            if ($filter === $middleware) {
                unset($this->filterStack[$key]);
                break;
            }
        }
        return $this;
    }

    /*
     * ioc injector 
     */

    /**
     * 
     * @return View
     */
    public function view() {
        return $this->container->view;
    }

    /**
     * 
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response() {
        return $this->container->response;
    }

    /**
     * 
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function request() {
        return $this->container->request;
    }

    /**
     * 
     * @return \Psr\Container\ContainerInterface
     */
    public function dic() {
        return $this->container;
    }

}
