<?php

/*
 * 
 */
namespace rootedit\core;

/**
 * @author Romain Flacher <romain.flacher at gmail.com>
 */
class View {
//HtmlTemplateInculdeRenderer


    public function __construct($tplClass = Template::class) {

        $this->{'#'} = new $tplClass(function() {
            return implode('', array_values(get_object_vars($this)));
        }, 'caca');

        //$this->{'#'} = new Template(function(){ return json_encode($this);});
    }

    public function setView($slot, $tpl) {
        $this->{$slot} = new Template($this, $tpl);
    }

    public function render() {
        return $this->{'#'}->__toString();
    }

    public function clear() {
        foreach ($this as $key => $value) {
            unset($this->{$key});
        }
    }


    public function helpers($className = null) {
        if (is_null(className)) {
            return new \outputHelper();
        } else {

            throw new Exception('not implemented yet');
//           $className= __NAMESPACE__ . '\\' . $className;
//             return new $className();
//           
            //ou alors ????
            // return $this->DIC->{$className};
        }
    }

}

class Dummy implements \ArrayAccess, \Countable {

    public function __toString() {
        return '';
    }

    public function __get($name) {
        return $this;
    }

    public function offsetExists($offset) {
        false;
    }

    public function offsetGet($offset) {
        return $this;
    }

    public function offsetSet($offset, $value) {
        
    }

    public function offsetUnset($offset) {
        
    }

    public function count() {
        return 0;
    }

}

class Template {

    private $slot;
        private static $dummy;

    public function __construct($slot, $tpl) {
        $this->slot = $slot;
        $this->tpl = $tpl;
    }

    public function __toString() {
        extract(get_object_vars($this->slot), EXTR_REFS);
        $tpl = $this->tpl;
        $this->tpl = null;
        include $tpl;
        return '';
    }
    
    
   public function __get($name) {
        return isset(self::$dummy) ? self::$dummy : self::$dummy = new Dummy;
    }

}
