<?php

namespace DB;

use DB\Interfaces\Entity as EntityInterface;

/**
 * Class Entity
 * @package DB
 *
 */
class Entity implements EntityInterface
{

    protected $data;
    protected $primaryKey;

    /**
     * Entity constructor.
     * @param array $data
     * @param string $primaryKey
     */
    public function __construct(array $data = [], $primaryKey = 'id')
    {
        $this->data = new \ArrayObject($data);
        $this->primaryKey = $primaryKey;
    }

    /**
     * Получить идентификатор сущности
     * @return mixed
     */
    public function getId()
    {
        return $this->__get($this->primaryKey);
    }

    /**
     * Получить экземпляр сущности с идентификатором
     * @param $id
     * @return $this|Entity|static
     */
    public function withId($id)
    {
        if($this->__get($this->primaryKey) == $id){
            return $this;
        }
        $instance = clone $this;
        $instance->data->offsetSet($this->primaryKey, $id);
        return $instance;
    }

    /**
     * Магия получения значений
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $value = $this->data->offsetExists($name)
            ? $this->data->offsetGet($name)
            : null;
        if(is_callable($value)){
            return $this->data[$name] = $value();
        }
        return $value;
    }

    /**
     * Магия наличия поля
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->data->offsetExists($name);
    }

    /**
     * Магия получения экземпляра сущности с новым значением поля
     * @param $name
     * @param $arguments
     * @return $this|Entity|static
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^with([a-z0-9_]+)/i', $name, $matches)) {
            $value = array_shift($arguments);
            if($this->__get(lcfirst($matches[1])) == $value){
                return $this;
            }
            $instance = clone $this;
            $instance->data->offsetSet(lcfirst($matches[1]), $value);
            return $instance;
        }
        if (preg_match('/^get([a-z0-9_]+)/i', $name, $matches)) {
            return $this->__get(lcfirst($matches[1]));
        }
        throw new Exception('Неизвестный метод');
    }

    /**
     * Получить сущность в виде массива
     * @return array
     */
    public function toArray()
    {
        $array = $this->data->getArrayCopy();
        foreach ($array as $key => $value) {
            if($value instanceof EntityInterface){
                $array[$key] = $value->toArray();
            }
        }
        return $array;
    }
}