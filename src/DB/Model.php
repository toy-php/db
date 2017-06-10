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

}