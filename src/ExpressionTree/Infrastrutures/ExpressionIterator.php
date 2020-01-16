<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\Infrastrutures;

use StKevich\ExpressionTree\ExpressionInterface;

class ExpressionIterator implements \Iterator
{
    /** @var ExpressionInterface[] */
    protected array $items;

    /**
     * ExpressionIterator constructor.
     * @param ExpressionInterface ...$expressions
     */
    public function __construct(ExpressionInterface ...$expressions)
    {
        $this->items = $expressions;
    }

    /**
     * @param ExpressionInterface $expressions
     */
    public function add(ExpressionInterface $expressions)
    {
        $this->items[] = $expressions;
    }

    /**
     * @return ExpressionInterface|null
     */
    public function current(): ExpressionInterface
    {
        return current($this->items);
    }

    /**
     * @return ExpressionInterface|null
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @return int|null
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        $key = key($this->items);
        return ($key !== null && $key !== false);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->items);
    }

}