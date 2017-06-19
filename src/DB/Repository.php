<?php

namespace DB;

use DB\Interfaces\Table as TableInterface;
use DB\Interfaces\DataBase as DataBaseInterface;
use DB\Interfaces\Repository as RepositoryInterface;
use DB\Interfaces\Model as ModelInterface;
use DB\Interfaces\Entity as EntityInterface;

abstract class Repository extends Collection implements RepositoryInterface
{

    /**
     * Объект таблицы
     * @var TableInterface
     */
    protected $table;

    public function __construct(DataBaseInterface $dataBase, $modelType = null)
    {
        $this->table = $this->buildTable($dataBase);
        parent::__construct($modelType ?: ModelInterface::class);
    }

    /**
     * Инициализация таблицы
     * @param DataBaseInterface $dataBase
     * @return TableInterface
     */
    abstract protected function buildTable(DataBaseInterface $dataBase);

    /**
     * @param EntityInterface $entity
     * @return ModelInterface
     */
    protected function buildModel(EntityInterface $entity)
    {
        return new Model($entity);
    }

    /**
     * Получить модель по идентификатору
     * @param $id
     * @return ModelInterface|null
     */
    public function findById($id)
    {
        $model = $this[$id];
        if (!empty($model)) {
            return $model;
        }
        $entity = $this->table[$id];
        if (empty($entity)) {
            return null;
        }
        $model = $this->buildModel($entity);
        $this->checkType($model);
        return $this[$id] = $model;
    }

    /**
     * Получить модель согласно критериям
     * @param array $criteria
     * @return Model
     */
    public function find(array $criteria)
    {
        $entity = $this->table->find($criteria);
        $model = $this[$entity->getId()];
        if (empty($model)) {
            $model = $this->buildModel($entity);
            $this->checkType($model);
            $this[$entity->getId()] = $model;
        }
        return $model;
    }

    /**
     * Получить коллекцию моделей
     * @param array $criteria
     * @return \DB\Interfaces\Collection
     */
    public function findAll(array $criteria)
    {
        $collection = $this->table->findAll($criteria);
        $modelsCollection = new Collection($this->type);
        /** @var Entity $entity */
        foreach ($collection as $entity) {
            $model = $this[$entity->getId()];
            if (empty($model)) {
                $model = $this->buildModel($entity);
                $this->checkType($model);
                $this[$entity->getId()] = $model;
            }
            $modelsCollection[] = $model;
        }
        return $modelsCollection;
    }

    /**
     * Сохранить модель
     * @param ModelInterface $model
     * @return void В случае ошибки сохранения будет сгенерировано исключение
     * на уровне преобразователя
     */
    public function save(ModelInterface $model)
    {
        $this->checkType($model);
        $entity = $model->getEntity();
        if (!empty($entity)) $this->table[] = $entity;
    }

    /**
     * Удалить модель
     * @param ModelInterface $model
     * @return void В случае ошибки удаления будет сгенерировано исключение
     * на уровне преобразователя
     */
    public function delete(ModelInterface $model)
    {
        $this->checkType($model);
        $entity = $model->getEntity();
        if (!empty($entity)) unset($this->table[$entity->getId()]);
    }

}