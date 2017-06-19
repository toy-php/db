<?php

namespace DB\Interfaces;

interface Repository
{

    /**
     * Получить модель по идентификатору
     * @param $id
     * @return Model
     */
    public function findById($id);

    /**
     * Получить модель согласно критериям
     * @param array $criteria
     * @return Model
     */
    public function find(array $criteria);

    /**
     * Получить коллекцию моделей
     * @param array $criteria
     * @return \DB\Interfaces\Collection
     */
    public function findAll(array $criteria);

    /**
     * Сохранить модель
     * @param Model $model
     * @return void В случае ошибки сохранения будет сгенерировано исключение
     * на уровне преобразователя
     */
    public function save(Model $model);

    /**
     * Удалить модель
     * @param Model $model
     * @return void В случае ошибки удаления будет сгенерировано исключение
     * на уровне преобразователя
     */
    public function delete(Model $model);
}