<?php

include '../src/DB/Interfaces/Entity.php';
include '../src/DB/Entity.php';
include '../src/DB/Exception.php';
include '../src/DB/Container.php';

class ContainerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \DB\Container
     */
    protected $container;

    public function setUp()
    {
        $this->container = new \DB\Container(\DB\Interfaces\Entity::class);
    }


    /**
     * Тест соответствию интерфейса
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(\ArrayAccess::class, $this->container);
    }

    /**
     * Тест добавления сущности в коллекцию
     */
    public function testOffsetSetGet()
    {
        $entity =  new \DB\Entity(['id' => 1]);
        $this->container[] = $entity;
        $this->assertTrue($this->container[0] === $entity);
    }

    /**
     * Тест удаления сущности из коллекции
     */
    public function testOffsetUnset()
    {
        $entity =  new \DB\Entity(['id' => 1]);
        $this->container[] = $entity;
        unset($this->container[0]);
        $this->assertTrue($this->container[0] === null);
    }

    /**
     * Тест наличия сущности в коллекции по ключу
     */
    public function testOffsetIsset()
    {
        $entity =  new \DB\Entity(['id' => 1]);
        $this->container[] = $entity;
        $this->assertTrue(isset($this->container[0]));
    }

    /**
     * Тест наличия сущности в коллекции
     */
    public function testContains()
    {
        $entity = new \DB\Entity(['id' => 1]);
        $this->container[] = $entity;
        $this->assertTrue($this->container->contains($entity));
    }

    /**
     * Тест проверки типа добавляемого объекта
     */
    public function testCheckType()
    {
        $this->expectException(\DB\Exception::class);
        $this->container[] = new stdClass();
    }

}
