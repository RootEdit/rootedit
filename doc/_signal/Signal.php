<?php

namespace fr\webetplus\rootedit\signal;

/**
 * Description of Signal
 *
 * @author 
 */
class Signal {

    private $slots;

    /**
     * Leprobléme n'est il pas de devoir instancier les objets pour les ier ce qui ne rend pas valide dans le cas d'objet instancier en différé par ico style micro conteneur 
     * on doit pouvoir instancier en diférer avec un micro conteneur???? ou alors si ca se trouve on a pas besoin pour application style liaison 
     * 

     */
    public function __construct(callable $listener = null) {
       $this->slots= new Slot($listener);
    }
    
    public function connect(callable $listener) {
        return $this->slots = $this->slots->add($listerner);
    }
    
    public function __invoke(...$args) {
        $slots = $this->slots;
        $next (...$args);
    }
    

//    static public function connect(callable &$signal, callable $slot) {
//        $signal = is_null($signal) 
//                ? function(...$args) use($slot) { $slot(...$args);} 
//                : function(...$args) use($signal, $slot) {$signal(...$args);$slot(...$args);};
//    }
//    static public function connect2(callable &$signal, callable $slot) {
//        $signal = is_null($signal) 
//                ? function(...$args) use($slot) { $slot(...$args);} 
//                : function(...$args) use($signal, $slot) {$signal(...$args);$slot(...$args);};
//    }

}
