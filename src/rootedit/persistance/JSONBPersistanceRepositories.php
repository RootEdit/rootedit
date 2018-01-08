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
 * @author romai
 */
//extends Repository
class MysqlPersistanceRepositories implements PersistanceResitory { // implements fr\webetplus\rootedit\persistance\IPersistanceOrientedRepositories {

    private $className;
    private $DIC;
    private $mapping;

    /**
     *
     * @var \PDOStatement 
     */
    private $preparedObjectOfId;
    private $preparedRemove;
    private $tableName;
    private $preparedSave;
    private $preparedAllObject;

    public function __construct(\Psr\Container\ContainerInterface $DIC, $className = 'stdClass', $mapping = null) {
        $this->DIC = $DIC;
        $this->className = $className;
        $this->mapping = $mapping;
    }

    public function nextIdentity() {
        
    }

    public function objectOfId($objectId) {
        if (!isset($this->preparedObjectOfId)) {
            if (is_null($this->mapping)) {
                $this->preparedObjectOfId = $this->DIC->dataSource->prepare('Select * FROM ' . $this->className . ' where id_' . $this->className . '= ?');
            } else {
                $this->preparedObjectOfId = $this->DIC->dataSource->prepare('Select ' . trim(str_replace(':', ' as ', json_encode($this->mapping)), '{}') . ' FROM ' . $this->className . ' where id_' . $this->className . '= ?');
            }
        }
        $this->preparedObjectOfId->execute([$objectOfId]);
        return $this->preparedObjectOfId->fetchObject($this->className);
    }

    public function remove($object) {
        if (!isset($this->preparedRemove)) {
            $this->preparedRemove = $this->DIC->dataSource->prepare('DELETE FROM ' . $this->className . ' where id_' . $this->className . '= ?');
        }
        return $this->preparedRemove->execute($object->{'id_' . $this->className});
    }

    public function removeAll($objectArray) {
        foreach ($objectArray as $object) {
            $this->remove($object);
        }
    }

    public function save($object) {
        if (!isset($this->preparedSave)) {
            $setFields = array();
            $setFields2 = array();
            
            if (is_null($this->mapping)) {
                foreach ($object as $key => $value) {
                    $setFields[] = $key . "= :" . $key;
                    $keys[] = $key ;
                }
                 $this->preparedSave = $this->DIC->dataSource->prepare( 'INSERT INTO '. $this->tableName .'  ('.  implode(',', $keys).') VALUES ( :'.implode(',:', $keys).') ON DUPLICATE KEY UPDATE '.implode(',', $setFields));
                } else {
                foreach ($this->mapping as $key => $value) {
                    $setFields[] = $key . "= :" . $value;
                }
               $this->preparedSave = $this->DIC->dataSource->prepare( 'INSERT INTO '. $this->tableName .'  ('.  implode(',', key($this->mapping)).') VALUES ( :'.implode(',:', array_values($this->mapping)).') ON DUPLICATE KEY UPDATE '.implode(',', $setFields));
            }
        }
        
        foreach ( $object as $key => $value) {
            $values[$key.'2']=$value[$key]=$value;
        }
        return $this->preparedSave->execute($values);
    }

    public function saveAll($objectArray) {
        foreach ($objectArray as $object) {
            $this->save($object);
        }
    }

    public function size() {
        
    }

    public function allObjects() {
        if (!isset($this->preparedAllObject)) {
            $this->preparedAllObject = $this->DIC->dataSource->prepare('Select * FROM ' . $this->className . ' ;');
        }
        $this->preparedAllObject->execute();
        while ($row = $this->preparedAllObject->fetchObject($this->className)) {
            yield $row;
        };
    }

//    public function byQuery($query) {
//        $p = parse_url($query, PHP_URL_PATH);
//        parse_str(parse_url($query, PHP_URL_QUERY), $q);
//    }

    public function beginTransaction() {
        $this->DIC->dataSource->beginTransaction();
    }

    public function commit() {
        $this->DIC->dataSource->commit();
    }

    public function rollBack() {
        $this->DIC->dataSource->rollBack();
    }

}
