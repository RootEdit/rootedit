<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fr\webetplus\rootedit\persistance;

/**
 * Description of DomaineRegistery
 *
 * @author romai
 */
class DomaineRegistery implements \Psr\Container\ContainerInterface {

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $dic;
    
    public function __construct(\Psr\Container\ContainerInterface $container=null) {
       $this->dic = $container;
    }

    public function setContainer(\Psr\Container\ContainerInterface $container) {
        $this->dic = $container;
    }

    public function __get($name) {
        return $this->{$name} = $this->dic->has($name) ? $this->dic->get($name) : new PersistanceRepositories($this->dic, $name);
    }

    public function get($id) {
//        return $this->dic->get($id);
        return $this->{$id};
    }

    public function has($id) {
        return $this->dic->has($id)||isset($this->{$id});
    }

}
