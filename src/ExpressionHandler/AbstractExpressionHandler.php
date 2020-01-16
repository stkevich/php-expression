<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler;

use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionException;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterResultNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

abstract class AbstractExpressionHandler
{

    /**
     * @param ExpressionInterface $expression
     * @return bool|mixed|string
     * @throws ExpressionException
     */
    protected function recursiveHandling(ExpressionInterface $expression)
    {
        switch (true) {
            case $expression instanceof MultiplyExpressionResultInterface:
                return $this->processingExpressionMultiplyResult($expression);
                break;
            case $expression instanceof DoubleExpressionResultInterface:
                return $this->processingExpressionDoubleResult($expression);
                break;
            case $expression instanceof SingleExpressionResultInterface:
                return $this->processingExpressionSingleResult($expression);
                break;
            case $expression instanceof AbstractParameterResultNode:
                return $this->processingParameter($expression);
                break;
        }

        throw new ExpressionException("Can't handle expression: " . get_class($expression));
    }


    /**
     * @param MultiplyExpressionResultInterface $expression
     * @return mixed
     */
    abstract protected function processingExpressionMultiplyResult(MultiplyExpressionResultInterface $expression);

    /**
     * @param DoubleExpressionResultInterface $expression
     * @return mixed
     */
    abstract protected function processingExpressionDoubleResult(DoubleExpressionResultInterface $expression);

    /**
     * @param SingleExpressionResultInterface $expression
     * @return mixed
     */
    abstract protected function processingExpressionSingleResult(SingleExpressionResultInterface $expression);

    /**
     * @param AbstractParameterResultNode $expression
     * @return mixed
     */
    abstract protected function processingParameter(AbstractParameterResultNode $expression);

}
