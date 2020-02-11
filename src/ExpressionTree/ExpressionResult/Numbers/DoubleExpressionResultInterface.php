<?php

namespace StKevich\ExpressionTree\ExpressionResult\Numbers;

use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;

/**
 * Interface DoubleInputExpressionInterface
 * @package StKevich\ExpressionTree\ExpressionInputsNumber
 */
interface DoubleExpressionResultInterface extends ExpressionNodesInterface
{
    /**
     * @return ExpressionInterface
     */
    public function getLeftExpression(): ExpressionInterface;

    /**
     * @return ExpressionInterface
     */
    public function getRightExpression(): ExpressionInterface;

}
