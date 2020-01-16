<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler\PDO;

use StKevich\ExpressionHandler\AbstractExpressionHandler;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionException;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterResultNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;

class PDOExpressionHandler extends AbstractExpressionHandler
{
    const RESULT_VIEW_TYPE = 'sql';
    const DEFAULT_START_INDEX = 1;

    /** @var int */
    protected int $index;

    /** @var int */
    protected int $startIndex;

    public function __construct(int $startIndex = self::DEFAULT_START_INDEX)
    {
        $this->startIndex = $startIndex;
    }

    /**
     * @param ExpressionInterface $expression
     * @param int|null $startIndex
     * @return PDOExpressionResult
     * @throws ExpressionException
     */
    public function handle(ExpressionInterface $expression): PDOExpressionResult
    {
        $this->index = $this->startIndex;

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
     * @return PDOExpressionResult
     * @throws ExpressionException
     */
    protected function processingExpressionMultiplyResult(MultiplyExpressionResultInterface $expression): PDOExpressionResult
    {
        $values = [];
        $expressionsResult = [];
        foreach ($expression->getExpressionIterator() as $internalExpression) {
            $internalExpressionResult = $this->processingInternalExpression($internalExpression);
            $expressionsResult[] = $internalExpressionResult->getExpressionString();
            if (!empty($internalExpressionResult->getData())) {
                $values += $internalExpressionResult->getData();
            }
        }
        $glue = sprintf(' %s ', $expression->standardGlue(self::RESULT_VIEW_TYPE));
        $sql = implode($glue, $expressionsResult);
        return new PDOExpressionResult($sql, $values);
    }

    /**
     * @param DoubleExpressionResultInterface $expression
     * @return PDOExpressionResult
     * @throws ExpressionException
     */
    protected function processingExpressionDoubleResult(DoubleExpressionResultInterface $expression): PDOExpressionResult
    {
        $left = $this->processingInternalExpression($expression->getLeftExpression());
        $right = $this->processingInternalExpression($expression->getRightExpression());
        $values = $left->getData() + $right->getData();
        $sql = sprintf('%s %s %s',
            $left->getExpressionString(),
            $expression->standardGlue(self::RESULT_VIEW_TYPE),
            $right->getExpressionString(),
        );
        return new PDOExpressionResult($sql, $values);
    }

    /**
     * @param SingleExpressionResultInterface $expression
     * @return PDOExpressionResult
     * @throws ExpressionException
     */
    protected function processingExpressionSingleResult(SingleExpressionResultInterface $expression): PDOExpressionResult
    {
        $expressionsResult = $this->processingInternalExpression($expression->getInternalExpression());
        $values = $expressionsResult->getData();
        $sql = sprintf(
            '%s(%s)',
            $expression->standardGlue(self::RESULT_VIEW_TYPE),
            $expressionsResult->getExpressionString()
        );
        return new PDOExpressionResult($sql, $values);
    }

    /**
     * @param AbstractParameterResultNode $node
     * @return PDOExpressionResult
     */
    protected function processingParameter(AbstractParameterResultNode $node): PDOExpressionResult
    {
        $values = [];
        if ($node instanceof KeyNode) {
            $sql = $node->get();
        }
        else {
            $values[$this->index] = $node->get();
            $sql = sprintf(':%s', $this->index++);
        }
        return new PDOExpressionResult($sql, $values);
    }

    /**
     * @param ExpressionInterface $expression
     * @return PDOExpressionResult
     * @throws ExpressionException
     */
    protected function processingInternalExpression(ExpressionInterface $expression): PDOExpressionResult
    {
        $result = $this->recursiveHandling($expression);
        switch (true) {
            case $expression instanceof MultiplyExpressionResultInterface:
                $result = new PDOExpressionResult(
                    sprintf('(%s)', $result->getExpressionString()),
                    $result->getData()
                );
                break;
            default:
                break;
        }
        return $result;
    }

}
