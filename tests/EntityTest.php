<?php

include '../src/DB/Interfaces/Entity.php';
include '../src/DB/Entity.php';

class EntityTest extends \PHPUnit_Framework_TestCase
{

    protected $data = [
        'id' => 1,
        'foo' => 'bar'
    ];

    /**
     * @var \DB\Entity
     */
    protected $entity;

    public function setUp()
    {
        $this->entity = new \DB\Entity($this->data);
    }

    /**
     * Тест соответствию интерфейса
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(\DB\Interfaces\Entity::class, $this->entity);
    }

    /**
     * Тест получения идентификатора сущности
     */
    public function testGetId()
    {
        $id = $this->entity->getId();
        $this->assertTrue($id === 1);
    }

    /**
     * Тест магического метода получения свойств сущности
     */
    public function testMagicGetProperty()
    {
        $value = $this->entity->foo;
        $this->assertTrue($value === 'bar');
    }

    /**
     * Тест магического метода получения свойств сущности через геттер
     */
    public function testMagicGetMethod()
    {
        $value = $this->entity->getFoo();
        $this->assertTrue($value === 'bar');
    }

    /**
     * Тест установки нового идентификатора сущности
     */
    public function testWithId()
    {
        $entity = $this->entity->withId(2);
        $this->assertTrue($entity->getId() === 2);
    }

    /**
     * Тест установки свойств сущности через магический метод
     */
    public function testMagicWithMethod()
    {
        $entity = $this->entity->withFoo('baz');
        $this->assertTrue($entity->getFoo() === 'baz');
    }

    /**
     * Тест получаемого объекта на соответствие интерфейсу,
     * при установки новых значений.
     * Должен возвращаться объект реализующий интерфейс сущности
     */
    public function testFluentInstanceOf()
    {
        $entity = $this->entity->withFoo('baz');
        $this->assertInstanceOf(\DB\Interfaces\Entity::class, $entity);
    }

    /**
     * Тест персистентности при установки новых значений сущности
     * должен создаваться новый объект, исходный объект меняться не дожен.
     */
    public function testPersistEntity()
    {
        $entity = $this->entity->withId(2);
        $this->assertTrue($entity !== $this->entity);
    }

    /**
     * Тест получения данных сущности в виде массива
     */
    public function testToArray()
    {
        $data = $this->entity->toArray();
        $this->assertTrue($data === $this->data);
    }

}
