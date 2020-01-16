<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleParameterResultInterface;
use StKevich\ExpressionTree\ExpressionInterface;

abstract class AbstractParameterResultNode implements ExpressionInterface
{
    /**
     * @return mixed
     */
    abstract public function get();

    /**
     * @param AbstractParameterResultNode $parameter
     * @return bool
     */
    abstract public function is(AbstractParameterResultNode $parameter): bool;

}
