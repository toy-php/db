<?php

include '../src/DB/Interfaces/Entity.php';
include '../src/DB/Interfaces/Collection.php';
include '../src/DB/Exception.php';
include '../src/DB/Entity.php';
include '../src/DB/Container.php';
include '../src/DB/Collection.php';

class CollectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \DB\Entity
     */
    protected $entities;

    /**
     * @var \DB\Collection
     */
    protected $collection;

    public function setUp()
    {
        $this->entities[] = new \DB\Entity(['id' => 1, 'prop' => 'foo']);
        $this->entities[] = new \DB\Entity(['id' => 2, 'prop' => 'bar']);
        $this->entities[] = new \DB\Entity(['id' => 3, 'prop' => 'baz']);
        $this->collection = new \DB\Collection(\DB\Interfaces\Entity::class, $this->entities);
    }

    /**
     * Тест соответствию интерфейса
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(\DB\Interfaces\Collection::class, $this->collection);
    }

    /**
     * Тест получения сущности по ключу
     */
    public function testOffsetGet()
    {
        $this->assertTrue($this->collection[0] === $this->entities[0]);
    }

    /**
     * Тест получения количества элементов коллеции
     */
    public function testCount()
    {
        $this->assertTrue(count($this->collection) === 3);
    }

    /**
     * Тест добавления сущности в коллекцию
     */
    public function testOffsetSet()
    {
        $this->collection[] = new \DB\Entity(['id' => 4]);
        $this->assertTrue(count($this->collection) === 4);
    }

    /**
     * Тест удаления сущности из коллекции
     */
    public function testOffsetUnset()
    {
        unset($this->collection[2]);
        $this->assertTrue(count($this->collection) === 2);
    }

    /**
     * Тест наличия сущности в коллекции по ключу
     */
    public function testOffsetIsset()
    {
        $this->assertTrue(isset($this->collection[2]));
    }

    /**
     * Тест наличия сущности в коллекции
     */
    public function testContains()
    {
        $entity = new \DB\Entity(['id' => 4]);
        $this->collection[] = $entity;
        $this->assertTrue($this->collection->contains($entity));
    }

    /**
     * Тест проверки типа добавляемого объекта
     */
    public function testCheckType()
    {
        $this->expectException(\DB\Exception::class);
        $this->collection[] = new stdClass();
    }

    /**
     * Тест поиска сущности
     */
    public function testSearch()
    {
        $entity = $this->collection->search('prop', 'foo');
        $this->assertTrue($entity === $this->entities[0]);
    }

    /**
     * Тест перебора сущностей коллекции в цикле
     */
    public function testIterate()
    {
        $n = 0;
        foreach ($this->collection as $entity) {
            $this->assertTrue($entity === $this->entities[$n]);
            ++$n;
        }
    }

    /**
     * Тест сортировки коллекции
     */
    public function testSort()
    {
        $this->collection->sort(function ($a, $b){
            if ($a->id == $b->id) {
                return 0;
            }
            return ($a->id > $b->id) ? -1 : 1;
        });

        $n = 2;
        foreach ($this->collection as $entity) {
            $this->assertTrue($entity === $this->entities[$n]);
            --$n;
        }
    }

    /**
     * Тест сокращения коллекции к единственному значению
     */
    public function testReduce()
    {
        $result = $this->collection->reduce(function ($carry, $item) {
            $carry += $item->id;
            return $carry;
        });
        $this->assertTrue($result === 6);
    }

    /**
     * Тест преобразования коллекции
     */
    public function testMap()
    {
        $collection = $this->collection->map(function ($entity){
            return $entity->withId($entity->getId() + 3);
        });
        $this->assertTrue($collection[0]->id === 4);
        $this->assertTrue($collection[1]->id === 5);
        $this->assertTrue($collection[2]->id === 6);
    }

    /**
     * Тест фильтрации коллекции
     */
    public function testFilter()
    {
        $collection = $this->collection->filter(function ($entity){
            return $entity->id > 1 and $entity->id < 4;
        });
        $this->assertTrue(count($collection) === 2);
    }

    /**
     * Тест преобразования коллекции в массив
     */
    public function testToArray()
    {
        $array = $this->collection->toArray();
        $n = 0;
        foreach ($array as $item) {
            $this->assertTrue($item === $this->entities[$n]->toArray());
            ++$n;
        }
    }

}
