<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Types\IntegerExpressionResultInterface;

class IntegerNode extends AbstractParameterNode implements IntegerExpressionResultInterface
{
    /** @var int */
    protected int $value;

    /**
     * KeyParameterExpression constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function get(): int
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function exec(): int
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
