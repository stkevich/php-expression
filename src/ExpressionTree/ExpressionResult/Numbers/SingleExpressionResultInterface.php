<?php

namespace StKevich\ExpressionTree\ExpressionResult\Numbers;

use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;

/**
 * Interface SingleInputExpressionInterface
 * @package StKevich\ExpressionTree\ExpressionResultTypes
 */
interface SingleExpressionResultInterface extends ExpressionNodesInterface
{
    /**
     * @return ExpressionInterface
     */
    public function getInternalExpression(): ExpressionInterface;

    /**
     * @param $value
     * @return mixed
     */
    public function implementsFunction($value);

}
