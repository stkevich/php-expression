<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\NumericalExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\StringExpressionResultInterface;

class KeyNode extends AbstractParameterResultNode implements
    BooleanExpressionResultInterface,
    StringExpressionResultInterface,
    NumericalExpressionResultInterface
{
    /** @var string */
    protected string $value;

    /**
     * KeyParameterExpression constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->value;
    }

    /**
     * @param AbstractParameterResultNode $parameter
     * @return bool
     */
    public function is(AbstractParameterResultNode $parameter): bool
    {
        return $parameter->get() === $this->get();
    }
}
