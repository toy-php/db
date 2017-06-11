<?php

include '../src/DB/Interfaces/Entity.php';
include '../src/DB/Interfaces/Collection.php';
include '../src/DB/Interfaces/Repository.php';
include '../src/DB/Exception.php';
include '../src/DB/Entity.php';
include '../src/DB/Container.php';
include '../src/DB/Collection.php';
include '../src/DB/Repository.php';

class DbRepository extends \DB\Repository
{

    /**
     * Инициализация таблицы
     * @param \DB\Interfaces\DataBase $dataBase
     * @return \DB\Interfaces\Table
     */
    protected function buildTable(\DB\Interfaces\DataBase $dataBase)
    {
        return $dataBase['test'];
    }

    /**
     * @param \DB\Interfaces\Entity $entity
     * @return \DB\Interfaces\Model
     */
    protected function buildModel(\DB\Interfaces\Entity $entity)
    {
        return new \DB\Model($entity);
    }
}

class RepositoryTest extends PHPUnit_Framework_TestCase
{

}
