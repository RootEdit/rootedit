<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fr\webetplus\rootedit\signal;

/**
 * Description of ClosureSignal
 *
 * @author romai
 */
class ClosureSignal {

    private $funct;

    public function connect(callable $listener) {
        $fct = $this->funct;
        $this->funct = function(...$args) use ($fct, $listerner) {
            $listerner(...$args);
            $fct(...$args);
        };
    }

    public function dispatch(...$arg) {
        $fct = $this->funct;
        $fct(...$arg);
    }

    public function __invoke(...$args) {
        $fct = $this->funct;
        $fct(...$arg);
    }

}
