<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler\ObjectSatisfaction;

use StKevich\ExpressionHandler\AbstractExpressionHandler;
use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\Exceptions\ExpressionException;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterNode;
use StKevich\ExpressionTree\ParameterNodes\BooleanNode;
use StKevich\ExpressionTree\ParameterNodes\FloatNode;
use StKevich\ExpressionTree\ParameterNodes\IntegerNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

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

        $expression = $this->recursiveHandling($this->expression);
        return (bool)$expression->exec();
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
    protected function processingExpressionMultiplyResult(MultiplyExpressionResultInterface $expression)
    {
        $class = get_class($expression);
        $nodes = [];
        foreach ($expression->getExpressionIterator() as $internalExpression) {
            $nodes[] = $this->recursiveHandling($internalExpression);
        }
        $expression = new $class(...$nodes);
        return $expression;
    }

    /**
     * @param DoubleExpressionResultInterface $expression
     * @return mixed
     * @throws ExpressionException
     */
    protected function processingExpressionDoubleResult(DoubleExpressionResultInterface $expression)
    {
        $class = get_class($expression);
        $left = $this->recursiveHandling($expression->getLeftExpression());
        $right = $this->recursiveHandling($expression->getRightExpression());
        $expression = new $class($left, $right);
        return $expression;
    }

    /**
     * @param SingleExpressionResultInterface $expression
     * @return mixed|SingleExpressionResultInterface|BooleanNode|FloatNode|IntegerNode|StringNode
     * @throws ExpressionException
     */
    protected function processingExpressionSingleResult(SingleExpressionResultInterface $expression)
    {
        $class = get_class($expression);
        $node = $this->recursiveHandling($expression->getInternalExpression());
        $expression = new $class($node);
        return $expression;
    }

    /**
     * @param AbstractParameterNode $expression
     * @return mixed|AbstractParameterNode|BooleanNode|FloatNode|IntegerNode|StringNode
     * @throws ExpressionException
     */
    protected function processingParameter(AbstractParameterNode $expression)
    {
        if ($expression instanceof KeyNode) {
            $name = $expression->get();
            $value = $this->object->$name;
            if (!isset($value)) {
                throw new ExpressionException(sprintf('Value %s is not define', $name));
            }
            if (is_string($value)) {
                $expression = new StringNode($value);
            }
            else if (is_int($value)) {
                $expression = new IntegerNode($value);
            }
            else if (is_float($value)) {
                $expression = new FloatNode($value);
            }
            else if (is_bool($value)) {
                $expression = new BooleanNode($value);
            }
        }
        return $expression;
    }

}
