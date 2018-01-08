<?php
use PHPUnit\Framework\TestCase;

/**
 * Description of ContainerTest
 *
 * @author romai
 */
class RepositoryTest extends TestCase
{
    private $repository;
    private $DIC;

    protected function setUp($dic){
        
        $this->DIC = new \fr\webetplus\rootedit\ioc\Container();
        $this->DIC->dataBase;
        $this->DIC->set('pdo', fr\webetplus\rootedit\persistance\MySQLPDO,'dataBase','username','password','server');  
        $this->DIC->set( 'articles',  \fr\webetplus\rootedit\persistance\PersistanceRepositories,'pdo' , 'article');
        $this->DIC->set( 'repository',  \fr\webetplus\rootedit\persistance\RepositoriesBuilder,'pdo');
       
    }
    
    public function testFu(){
        $this->container->articles->objectOfId(1);
        $this->container->voitures->objectOfId(1);
        $this->container->repository->messages->objectOfId(1);
    }
}

