<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Types\IntegerExpressionResultInterface;

class IntegerNode extends AbstractParameterResultNode implements IntegerExpressionResultInterface
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
     * @param AbstractParameterResultNode $parameter
     * @return bool
     */
    public function is(AbstractParameterResultNode $parameter): bool
    {
        return $parameter->get() == $this->get();
    }
}
