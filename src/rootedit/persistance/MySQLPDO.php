<?php

namespace fr\webetplus\rootedit\persistance;

/**
 * undocumented class
 *
 * @package default
 * @author
 */
class MySQLPDO extends \PDO {

//    private static $instance;
//
//    const MSQL = 0;

    /**
     * undocumented function
     *
     * @return void
     * @author
     */
    function __construct($dataBase, $username = 'root', $password = '', $server = 'localhost', $charset = 'utf8') {
//        try {
            /* PDO::MYSQL_ATTR_SSL_KEY    =>'/path/to/client-key.pem',
              PDO::MYSQL_ATTR_SSL_CERT=>'/path/to/client-cert.pem',
              PDO::MYSQL_ATTR_SSL_CA    =>'/path/to/ca-cert.pem'
             */

            parent::__construct("mysql:host=$server;dbname=$dataBase;charset=$charset", $username, $password, array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset",
                \PDO::ATTR_PERSISTENT => true
            ));
            //$this->exec("SET CHARACTER SET utf8");
//        } catch (\PDOException $ex) {
//          //  echo 'hoho : ' . $ex->getMessage();
//           // exit();
//        }
    }

//    /**
//     * undocumented function
//     *
//     * @return void
//     * @author
//     */
//    function getInstance() {
//
//        //TODO probl�me de la config si on met en propiété static c'est relou et en param c'est pas valable les autres fois ....
//        //return is_null(static::$instance)?static::$instance= new self():static::$instance;
//    }
//
//    public function query($statement) {
//        // echo '<p>'.$statement.'</p>';  
//        return parent::query($statement);
//    }
//
//    public function exec($statement) {
//        // echo '<p>'.$statement.'</p>';       
//        return parent::exec($statement);
//    }

}

// END
