<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions;

use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterNode;

abstract class AbstractParameterExpression implements ExpressionNodesInterface, DoubleExpressionResultInterface
{
    /** @var AbstractParameterNode */
    protected AbstractParameterNode $nodeLeft;

    /** @var AbstractParameterNode */
    protected AbstractParameterNode $nodeRight;

    /**
     * EqualExpression constructor.
     * @param AbstractParameterNode $parameterLeft
     * @param AbstractParameterNode $parameterRight
     */
    public function __construct(AbstractParameterNode $parameterLeft, AbstractParameterNode $parameterRight)
    {
        $this->nodeLeft = $parameterLeft;
        $this->nodeRight = $parameterRight;
    }

    /**
     * @return AbstractParameterNode
     */
    public function getLeftExpression(): AbstractParameterNode
    {
        return $this->nodeLeft;
    }

    /**
     * @return AbstractParameterNode
     */
    public function getRightExpression(): AbstractParameterNode
    {
        return $this->nodeRight;
    }



}
