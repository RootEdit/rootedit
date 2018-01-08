<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fr\webetplus\rootedit\signal;

/**
 * Description of Slot
 *
 * @author romai
 */

class Slot{

    private $courant;
    private $next;

    public function __construct(callable $listener = null) {
        $this->courant = $listener;
        $this->next=function(){};
    }
    
    public function add($listener) {
      return $this->next = new Slot($listener) ;
    }
    
    public function invoke(...$args) {
     
        $next = $this->next;
        $next (...$args);
        
        $courant = $this->courant;
        $courant(...$args);
    }
}




