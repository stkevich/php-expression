<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionInterface;

abstract class AbstractParameterNode implements ExpressionInterface
{
    /**
     * @return mixed
     */
    abstract public function get();

    /**
     * @param AbstractParameterNode $parameter
     * @return bool
     */
    abstract public function is(AbstractParameterNode $parameter): bool;

}
