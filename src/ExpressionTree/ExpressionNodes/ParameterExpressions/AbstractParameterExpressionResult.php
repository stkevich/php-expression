<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions;

use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;
use StKevich\ExpressionTree\ExpressionResult\Numbers\DoubleExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterResultNode;

abstract class AbstractParameterExpressionResult implements ExpressionNodesInterface, DoubleExpressionResultInterface
{
    /** @var AbstractParameterResultNode */
    protected AbstractParameterResultNode $nodeLeft;

    /** @var AbstractParameterResultNode */
    protected AbstractParameterResultNode $nodeRight;

    /**
     * EqualExpression constructor.
     * @param AbstractParameterResultNode $parameterLeft
     * @param AbstractParameterResultNode $parameterRight
     */
    public function __construct(AbstractParameterResultNode $parameterLeft, AbstractParameterResultNode $parameterRight)
    {
        $this->nodeLeft = $parameterLeft;
        $this->nodeRight = $parameterRight;
    }

    /**
     * @return AbstractParameterResultNode
     */
    public function getLeftExpression(): AbstractParameterResultNode
    {
        return $this->nodeLeft;
    }

    /**
     * @return AbstractParameterResultNode
     */
    public function getRightExpression(): AbstractParameterResultNode
    {
        return $this->nodeRight;
    }



}
