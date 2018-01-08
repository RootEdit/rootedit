<?php

namespace rootedit\ioc;

use Exception;
use Psr\Container\ContainerInterface;
use Psr\Container\Exception\ContainerExceptionInterface;
use Psr\Container\Exception\NotFoundExceptionInterface;

/**
 *  dependency injection containers.
 *  exposes methods to read its entries.
 */
class Container implements ContainerInterface {

    protected $s = array();
    private $delegateLookup;
    private $fetchedEntryDependenciesStrategy;

    public function __construct() {
        $this->delegateLookup();
    }

    /**
     * 
     * @param type $id
     * @param type $args
     */
    public function set($id, ...$args) {
        $this->s[$id] = $args;
    }

//    public function __call($name, $arguments) {
//        return $this->{$name};
//    }

    public function __get($id) {
        if (!isset($this->s[$id])) {
            throw new NotFoundException();
        }
        try {
            if (is_string($this->s[$id][0])) {
                return $this->{$id} = new $this->s[$id][0](...$this->fetchEntrysDependencies($id));
            }
            if (is_callable($this->s[$id][0])) {
                return $this->{$id} = $this->s[$id][0](...$this->fetchEntrysDependencies($id));
            }
            if (count($this->s[$id]) == 1) {
                return $this->{$id} = $this->s[$id][0];
            }
            return $this->{$id} = $this->s[$id];
        } catch (Exception $e) {
            throw new ContainerException();
        }
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     */
    public function get($id, ContainerInterface $delegateLookup = null) {
        if (!$this->has($id)) {
            throw new NotFoundException();
        }
        try {
            if ($delegateLookup !== null) {
                $hold = $this->delegateLookup;
                $this->delegateLookup($delegateLookup);
            }
            if (is_string($this->s[$id][0])) {
                $return = new $this->s[$id][0](...$this->fetchEntrysDependencies($id));
            } else if (is_callable($this->s[$id][0])) {
                $return = $this->s[$id][0](...$this->fetchEntrysDependencies($id));
            } else if (count($this->s[$id]) == 1) {
                $return = $this->s[$id][0];
            } else {
                $return  = $this->s[$id];
            }
            if (isset($hold)) {
                $this->delegateLookup($hold);
            }
            return $return;
        } catch (Exception $e) {
            throw new ContainerException();
        }
    }

    public function delegateLookup(ContainerInterface $delegateLookup = null) {
        $this->fetchedEntryDependenciesStrategy = (is_null($delegateLookup) || $delegateLookup instanceof static) ?
                function($containerScope, $entryId) {
            return $containerScope->{$entryId};
        } :
                function($containerScope, $entryId) {
            return $containerScope->get($entryId);
        };
        $this->delegateLookup = is_null($delegateLookup) ? $this : $delegateLookup;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     * @return boolean
     */
    public function has($id) {
        return isset($this->s[$id]) || isset($this->{$id});
    }

    private function fetchEntrysDependencies($entryID) {
        $startegy = $this->fetchedEntryDependenciesStrategy;
        $nb = count($this->s[$entryID]);
        for ($i = 1; $i < $nb; $i++) {
            yield $this->delegateLookup->has($this->s[$entryID][$i]) ? $startegy($this->delegateLookup, $this->s[$entryID][$i]) : $this->s[$entryID][$i];
        }
    }

}

class NotFoundException extends Exception implements NotFoundExceptionInterface {
    
}

class ContainerException extends Exception implements ContainerExceptionInterface {
    
}
