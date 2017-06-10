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

    public function __construct(DataBaseInterface $dataBase, $modelType)
    {
        $this->table = $this->buildTable($dataBase);
        parent::__construct($modelType);
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
    abstract protected function buildModel(EntityInterface $entity);

    /**
     * Получить модель по идентификатору
     * @param $id
     * @return ModelInterface|null
     */
    public function getById($id)
    {
        if (!isset($this[$id])) {
            $entity = $this->table[$id];
            if (empty($entity)) {
                return null;
            }
            $model = $this->buildModel($entity);
            $this->checkType($model);
            return $this[$id] = $model;
        }
        return $this[$id];
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