<?php

include '../src/DB/Interfaces/Entity.php';
include '../src/DB/Interfaces/Model.php';
include '../src/DB/Entity.php';
include '../src/DB/Model.php';

class ModelTest extends PHPUnit_Framework_TestCase
{

    protected $data = [
        'id' => 1,
        'foo' => 'bar'
    ];

    /**
     * @var \DB\Entity
     */
    protected $entity;

    /**
     * @var \DB\Model
     */
    protected $model;

    public function setUp()
    {
        $this->entity = new \DB\Entity($this->data);
        $this->model = new \DB\Model($this->entity);
    }

    /**
     * Тест соответствию интерфейса
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(\DB\Interfaces\Model::class, $this->model);
    }

    /**
     * Тест получения сущности
     */
    public function testGetEntity()
    {
        $entity = $this->model->getEntity();
        $this->assertTrue($entity === $this->entity);
    }

    /**
     * Тест магического метода получения идентификатора
     */
    public function testMagicGetEntityId()
    {
        $id = $this->model->id;
        $this->assertTrue($id === 1);
    }

    /**
     * Тест магического метода получения свойства
     */
    public function testMagicGetEntityProperty()
    {
        $foo = $this->model->foo;
        $this->assertTrue($foo === 'bar');
    }

    /**
     * Тест магического метода наличия свойства
     */
    public function testMagicIssetEntityProperty()
    {
        $result = isset($this->model->foo);
        $this->assertTrue($result);
    }

    /**
     * Тест получения данных в виде массива
     */
    public function testToArray()
    {
        $data = $this->model->toArray();
        $this->assertTrue($data === $this->data);
    }
}
