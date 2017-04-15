<?php

namespace DB\Interfaces;

interface Adapter
{

    /**
     * Получить мета-данные таблицы
     * @param string $table имя таблицы
     * @return array
     */
    public function getMeta($table);

    /**
     * Получить идентификатор последней вставленной записи
     * @param $primaryKey
     * @return mixed
     */
    public function lastInsertId($primaryKey);

    /**
     * Выполнение транзакции
     * @param $transaction
     * @return boolean
     */
    public function transaction($transaction);

    /**
     * Выбрать данные
     * @param string $table имя таблицы
     * @param array $condition критерии
     * @return \PDOStatement
     */
    public function select($table, array $condition = []);

    /**
     * Сохранить новые данные
     * @param string $table имя таблицы
     * @param array $data данные для сохранения
     * @return boolean
     */
    public function insert($table, $data);

    /**
     * Изменить существующие данные
     * @param string $table имя таблицы
     * @param array $data данные для сохранения
     * @param string|array $where критерии
     * @return boolean
     */
    public function update($table, $data, $where);

    /**
     * Удалить данные
     * @param string $table имя таблицы
     * @param string|array $where критерии
     * @return boolean
     */
    public function delete($table, $where);
}