<?php

use PHPUnit\Framework\TestCase;
use rootedit\ioc\Container;

/**
 * Description of ContainerTest
 *
 * @author romai
 */
class ContainerTest extends TestCase {

    private $container;

    protected function setUp() {
        $this->container = new Container();
    }

    public function testFetchParameters() {
        $this->container->param1 = 123;
        $this->assertSame($this->container->param1, 123);

        $this->container->param2 = 'hello';
        $this->assertSame($this->container->param2, 'hello');
    }

    public function testFetchAlredyInstanciedObject() {
        $dummy = new Dummy(123, 'hello');
        $this->container->set('dummy', $dummy);

        $this->assertSame($dummy, $this->container->dummy);
    }

    public function testFetchArray() {
        $this->container->set('dummy', 1, 2, 3);

        $this->assertSame([1, 2, 3], $this->container->dummy);
    }

    public function testFetchObject() {
        $this->container->param1 = 123;
        $this->container->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $dummy = $this->container->dummy;
        $this->assertNotNull($dummy);
        $this->assertSame($this->container->param1, $dummy->a);
        $this->assertSame($this->container->param2, $dummy->b);
    }

    public function testFetchSameReference() {
        $this->container->param1 = 123;
        $this->container->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $dummy = $this->container->dummy;
        $dummy2 = $this->container->dummy;
        $this->assertNotNull($dummy);
        $this->assertNotNull($dummy2);
        $this->assertSame($dummy, $dummy2);
    }

    public function testFetchFromCallable() {
        $this->container->param1 = 123;
        $this->container->param2 = 'hello';
        $this->container->set('dummy', function($p1, $p2) {
            return new Dummy($p1, $p2);
        }, 'param1', 'param2');
        $dummy = $this->container->dummy;
        $this->assertNotNull($dummy);
        $this->assertSame($this->container->param1, $dummy->a);
        $this->assertSame($this->container->param2, $dummy->b);
    }

    public function testCascadeFetch() {
        $this->container->param1 = 123;
        $this->container->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $this->container->set('dummy2', 'Dummy', 'dummy', 'param2');

        $dummy = $this->container->dummy;
        $dummy2 = $this->container->dummy2;

        $this->assertNotNull($dummy);
        $this->assertNotNull($dummy2);

        $this->assertSame($dummy2->a, $dummy);
        $this->assertSame($this->container->param2, $dummy2->b);
    }

    public function testGet() {
        $this->container->param1 = 123;
        $this->container->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $dummy = $this->container->get('dummy');
        $dummy2 = $this->container->get('dummy');
        $this->assertNotNull($dummy);
        $this->assertNotNull($dummy2);
        $this->assertSame($this->container->param1, $dummy->a);
        $this->assertSame($this->container->param2, $dummy->b);
        $this->assertNotSame($dummy, $dummy2);
    }

    public function testPermanantDdelegateLookup() {
        $container2 = new Container();
        $container2->param1 = 123;
        $container2->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $this->container->delegateLookup($container2);
        $dummy = $this->container->get('dummy');
     
        $this->assertSame($container2->param1, $dummy->a);
        $this->assertSame($container2->param2, $dummy->b);
    }

    public function testTemporairDelegateLookup() {
        $container2 = new Container();
        $container2->param1 = 123;
        $container2->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $dummy = $this->container->get('dummy',$container2);
        
        $this->assertSame($container2->param1, $dummy->a);
        $this->assertSame($container2->param2, $dummy->b);
    }

    public function testTemporairDelegateLookupWithAPermanantDdelegateLookup() {
        $container2 = new Container();
        $container2->param1 = 123;
        $container2->param2 = 'hello';
        $this->container->set('dummy', 'Dummy', 'param1', 'param2');
        $this->container->delegateLookup($container2);
        
        $container3 = new Container();
        $container3->param1 =456;
        $container3->param2 = 'world';
        
        $dummy = $this->container->get('dummy',$container3);
     
        $dummy2 = $this->container->get('dummy');
        
        $this->assertSame($container3->param1, $dummy->a);
        $this->assertSame($container3->param2, $dummy->b);
        $this->assertSame($container2->param1, $dummy2->a);
        $this->assertSame($container2->param2, $dummy2->b);
        
        
        
    }

    //reste tester les cas qui produisent des exceptions
}

class Dummy {

    public $a;
    public $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

}
