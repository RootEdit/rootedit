<?php
namespace fr\webetplus\rootedit\domaine;
/*
 *  Copyright (C) 2013 Web et Plus <contact at webetplus.fr>
 *
 *  This program is free software. It comes without any warranty, to
 *  the extent permitted by applicable law. You can redistribute it
 *  and/or modify it under the terms of the Do What The Fuck You Want
 *  To Public License, Version 2, as published by Sam Hocevar. See
 *  http://www.wtfpl.net/ for more details. 
 *
 */



/**
 * Description of Specification
 *
 * @author Web et Plus <contact at webetplus.fr>
 */
abstract class Specification {

    abstract public function isSatisfiedBy($aCandidateObject);

    final public function _and(Specification $aSpecification) {
        return new ConjunctionSpecification($this, $aSpecification);
    }

    final public function _or(Specification $aSpecification) {
        return new DisjunctionSpecification($this, $aSpecification);
    }

    final public function _not() {
        return new NegationSpecification($this);
    }

}

class NegationSpecification extends Specification {

    protected $specification;

    public function __construct(Specification $aSpecification) {
        $this->specification = $aSpecification;
    }

    public function isSatisfiedBy($aCandidateObject) {
        return !$this->specification->isSatisfiedBy($aCandidateObject);
    }

}

abstract class CompositeSpecification extends Specification {

    protected $spec1;
    protected $spec2;

    public function __construct(Specification $spec1, Specification $spec2) {
        $this->spec1 = $spec1;
        $this->spec2 = $spec2;
    }

}

class ConjunctionSpecification extends CompositeSpecification {

    public function __construct(Specification $spec1, Specification $spec2) {
        parent::__construct($spec1, $spec2);
    }
    public function isSatisfiedBy($aCandidateObject) {
        return $this->spec1->isSatisfiedBy($aCandidateObject) && $this->spec2->isSatisfiedBy($aCandidateObject);
    }
}

class DisjunctionSpecification extends CompositeSpecification {

     public function __construct(Specification $spec1, Specification $spec2) {
        parent::__construct($spec1, $spec2);
    }
     public function isSatisfiedBy($aCandidateObject) {
        return $this->spec1->isSatisfiedBy($aCandidateObject) || $this->spec2->isSatisfiedBy($aCandidateObject);
    }
}

//class NegationSpecification extends Specification {
//
//    protected $specification;
//
//    public function __construct(Specification $aSpecification) {
//        $this->specification = $aSpecification;
//    }
//
//    public function isSatisfiedBy($aCandidateObject) {
//        return !$this->specification->isSatisfiedBy($aCandidateObject);
//    }
//
//}
//
//abstract class CompositeSpecification extends Specification {
//
//    protected $specifications;
//
//    public function __construct() {
//        $this->specifications = func_get_args();
//    }
//
//}
//
//class ConjunctionSpecification extends CompositeSpecification {
//
//    public function __construct() {
//        parent::__construct();
//    }
//
//    public function isSatisfiedBy($aCandidateObject) {
//        foreach ($this->specifications as $specification) {
//            if (!$specification->isSatisfiedBy($aCandidateObject)) {
//                return FALSE;
//            }
//        }
//        return TRUE;
//    }
//
//}
//
//class DisjunctionSpecification extends CompositeSpecification {
//
//    public function __construct() {
//        parent::__construct();
//    }
//
//    public function isSatisfiedBy($aCandidateObject) {
//        foreach ($this->specifications as $specification) {
//            if ($specification->isSatisfiedBy($aCandidateObject)) {
//                return TRUE;
//            }
//        }
//        return FALSE;
//    }
//
//}
