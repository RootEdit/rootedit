<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fr\webetplus\rootedit\persistance;

/**
 * Description of PersistanceRepositories
 *
 * @author RootEdit
 */
interface PersistanceObjectRepository { 

    public function __construct(\Psr\Container\ContainerInterface $DIC, $className = 'stdClass', $mapping = null);

    public function nextIdentity();

    public function objectOfId($objectId) ;

    public function remove($object) ;

    public function removeAll($objectArray) ;

    public function save($object) ;

    public function saveAll($objectArray) ;

    public function size();

    public function allObjects();

//    public function byQuery($query);

    public function beginTransaction();

    public function commit();

    public function rollBack();

}
