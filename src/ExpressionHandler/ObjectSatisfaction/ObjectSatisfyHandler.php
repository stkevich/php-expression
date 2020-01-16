<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler\ObjectSatisfaction;

use StKevich\ExpressionHandler\AbstractExpressionHandler;
use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionException;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterResultNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;

class ObjectSatisfyHandler extends AbstractExpressionHandler
{
    /** @var object */
    protected object $object;

    /** @var ExpressionInterface */
    protected ExpressionInterface $expression;

    /**
     * ObjectSatisfyHandler constructor.
     * @param ExpressionInterface $expression
     */
    public function __construct(ExpressionInterface $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @param object $object
     * @return bool
     * @throws ExpressionException
     */
    public function handle(object $object): bool
    {
        $this->object = $object;

        return (bool)$this->recursiveHandling($this->expression);
    }

    /**
     * @param ExpressionInterface $expression
     * @return bool|mixed|string
     * @throws ExpressionException
     */
    protected function recursiveHandling(ExpressionInterface $expression)
    {
        return parent::recursiveHandling($expression);
    }


    /**
     * @param MultiplyExpressionResultInterface $expression
     * @return bool
     * @throws ExpressionException
     */
    protected function processingExpressionMultiplyResult(MultiplyExpressionResultInterface $expression): bool
    {
        $exprResult = [];
        foreach ($expression->getExpressionIterator() as $internalExpression) {
            $exprResult[] = $this->recursiveHandling($internalExpression);
        }
        return (bool) $expression->implementsFunction(...$exprResult);
    }

    /**
     * @param DoubleExpressionResultInterface $expression
     * @return bool
     * @throws ExpressionException
     */
    protected function processingExpressionDoubleResult(DoubleExpressionResultInterface $expression): bool
    {
        $leftParameter = $this->recursiveHandling($expression->getLeftExpression());
        $rightParameter = $this->recursiveHandling($expression->getRightExpression());
        return (bool) $expression->implementsFunction($leftParameter, $rightParameter);
    }

    /**
     * @param SingleExpressionResultInterface $expression
     * @return bool
     * @throws ExpressionException
     */
    protected function processingExpressionSingleResult(SingleExpressionResultInterface $expression): bool
    {
        return (bool)$expression->implementsFunction($this->recursiveHandling($expression->getInternalExpression()));
    }

    /**
     * @param AbstractParameterResultNode $expression
     * @return mixed|string
     */
    protected function processingParameter(AbstractParameterResultNode $expression)
    {
        switch (true) {
            case $expression instanceof KeyNode:
                $name = $expression->get();
                return (string) $this->object->$name;
                break;
            default:
                return $expression->get();
                break;
        }
    }

}
