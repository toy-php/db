<?php

namespace DB;

use DB\Interfaces\Entity as EntityInterface;
use  DB\Interfaces\Model as ModelInterface;

class Model implements ModelInterface
{

    protected $entity;

    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Получить сущность модели
     * @return EntityInterface|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Магия получения значений сущности
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->entity->$name;
    }

    /**
     * Магия наличия значений сущности
     * @param $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->entity->$name);
    }
}