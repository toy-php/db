<?php

namespace DB\Interfaces;

interface Repository
{

    /**
     * Получить модель по идентификатору
     * @param $id
     * @return Model
     */
    public function getById($id);

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