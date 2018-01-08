<?php
namespace fr\webetplus\rootedit\signal;

/**
 * Description of ArraySignal
 *
 * @author romai
 */
class ArraySignal {

    public function connect(callable $listener) {
        $this->slots[] = $slot;
    }

    public function dispatch(...$arg) {
        foreach ($this->slots as $slot) {
            $slot(...$arg);
        }
    }

    public function __invoke(...$args) {
        foreach ($this->slots as $slot) {
            $slot(...$arg);
        }
    }

}
