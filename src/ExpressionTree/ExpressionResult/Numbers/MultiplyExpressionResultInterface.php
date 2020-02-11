<?php

namespace StKevich\ExpressionTree\ExpressionResult\Numbers;

use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;
use StKevich\ExpressionTree\Infrastrutures\ExpressionIterator;

/**
 * Interface MultiplyInputExpressionInterface
 * @package StKevich\ExpressionTree\ExpressionInputsNumber
 */
interface MultiplyExpressionResultInterface extends ExpressionNodesInterface
{
    /**
     * @return ExpressionIterator
     */
    public function getExpressionIterator(): ExpressionIterator;

}
