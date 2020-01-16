<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\LogicExpressions;

use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;
use StKevich\ExpressionTree\Infrastrutures\ExpressionIterator;

class OrExpression extends AbstractLogicExpression implements BooleanExpressionResultInterface, MultiplyExpressionResultInterface
{
    /** @var ExpressionIterator */
    protected ExpressionIterator $expressions;

    /**
     * AndExpression constructor.
     * @param BooleanExpressionResultInterface ...$expressions
     */
    public function __construct(BooleanExpressionResultInterface ...$expressions)
    {
        $this->expressions = new ExpressionIterator(...$expressions);
    }

    /**
     * @param BooleanExpressionResultInterface ...$expressions
     */
    public function addExpression(BooleanExpressionResultInterface ...$expressions)
    {
        foreach ($expressions as $expression) {
            $this->expressions->add($expression);
        }
    }

    /**
     * @return ExpressionIterator
     */
    public function getExpressionIterator(): ExpressionIterator
    {
        return $this->expressions;
    }

    /**
     * @param mixed ...$values
     * @return bool|mixed
     */
    public function implementsFunction(...$values)
    {
        $result = false;
        foreach ($values as $value) {
            $result = $result || (bool)$value;
        }
        return $result;
    }

    /**
     * @param string|null $viewType
     * @return string
     */
    public function standardGlue(string $viewType = null): string
    {
        switch ($viewType) {
            default:
                return 'OR';
                break;
        }
    }

}
