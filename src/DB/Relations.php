<?php

namespace DB;

use DB\Interfaces\Entity as EntityInterface;

class Relations
{

    /**
     * @var \SplStack
     */
    protected $stack;

    /**
     * Middleware constructor.
     * @param callable $callable стартовая функция
     * @throws Exception
     */
    public function __construct($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new Exception('Неверная функция');
        }
        $this->stack = new \SplStack();
        $this->stack->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);
        $this->stack->push(function () use ($callable) {
            $arguments = func_get_args();
            $result = $callable(...$arguments);
            if(!$result instanceof EntityInterface){
                throw new Exception('Функция возвратила неверный результат');
            }
            return $result;
        });
    }

    /**
     * Добавление функции в стек
     * @param callable $callable
     * @return $this
     * @throws Exception
     */
    public function add($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new Exception('Неверная функция');
        }
        $next = $this->stack->top();
        $this->stack->push(
            function () use ($callable, $next) {
                $arguments = func_get_args();
                $result = $callable($next(...$arguments));
                if(!$result instanceof EntityInterface){
                    throw new Exception('Функция возвратила неверный результат');
                }
                return $result;
            }
        );
        return $this;
    }

    /**
     * Выполнение стека функций
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function __invoke(EntityInterface $entity)
    {
        $arguments = func_get_args();
        $callable = $this->stack->top();
        return $callable(...$arguments);
    }
}