<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Types\FloatExpressionResultInterface;

class FloatNode extends AbstractParameterNode implements FloatExpressionResultInterface
{
    /** @var float */
    protected float $value;

    /**
     * KeyParameterExpression constructor.
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function get(): float
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function exec(): float
    {
        return $this->get();
    }

    /**
     * @param AbstractParameterNode $parameter
     * @return bool
     */
    public function is(AbstractParameterNode $parameter): bool
    {
        return $parameter->get() == $this->get();
    }
}
