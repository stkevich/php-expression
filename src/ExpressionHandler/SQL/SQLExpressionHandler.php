<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler\SQL;

use StKevich\ExpressionHandler\AbstractExpressionHandler;
use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AbstractLogicExpression;
use StKevich\ExpressionTree\Exceptions\ExpressionException;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

class SQLExpressionHandler extends AbstractExpressionHandler
{
    const RESULT_VIEW_TYPE = 'sql';

    /**
     * @param ExpressionInterface $expression
     * @return string
     * @throws ExpressionException
     */
    public function handle(ExpressionInterface $expression): string
    {
        return $this->recursiveHandling($expression);
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
     * @return string
     * @throws ExpressionException
     */
    protected function processingExpressionMultiplyResult(MultiplyExpressionResultInterface $expression): string
    {
        $expressionsResult = [];
        foreach ($expression->getExpressionIterator() as $internalExpression) {
            $expressionsResult[] = $this->processingInternalExpression($internalExpression);
        }
        $glue = sprintf(' %s ', $expression->standardGlue(self::RESULT_VIEW_TYPE));
        return implode($glue, $expressionsResult);
    }

    /**
     * @param DoubleExpressionResultInterface $expression
     * @return string
     * @throws ExpressionException
     */
    protected function processingExpressionDoubleResult(DoubleExpressionResultInterface $expression): string
    {
        return sprintf(
            '%s %s %s',
            $this->processingInternalExpression($expression->getLeftExpression()),
            $expression->standardGlue(self::RESULT_VIEW_TYPE),
            $this->processingInternalExpression($expression->getRightExpression()),
        );
    }

    /**
     * @param SingleExpressionResultInterface $expression
     * @return string
     * @throws ExpressionException
     */
    protected function processingExpressionSingleResult(SingleExpressionResultInterface $expression): string
    {
        return sprintf(
            '%s(%s)',
            $expression->standardGlue(self::RESULT_VIEW_TYPE),
            $this->processingInternalExpression($expression->getInternalExpression())
        );
    }

    /**
     * @param AbstractParameterNode $expression
     * @return mixed|string
     */
    protected function processingParameter(AbstractParameterNode $expression): string
    {
        switch (true) {
            case $expression instanceof StringNode:
                return sprintf("'%s'", $expression->get());
            default:
                return (string)$expression->get();
        }
    }

    /**
     * @param ExpressionInterface $expression
     * @return string
     * @throws ExpressionException
     */
    protected function processingInternalExpression(ExpressionInterface $expression): string
    {
        switch (true) {
            case $expression instanceof AbstractLogicExpression:
                return sprintf('(%s)', $this->recursiveHandling($expression));
            default:
                return sprintf('%s', $this->recursiveHandling($expression));
        }
    }

}
