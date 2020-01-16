<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;

class BooleanNode extends AbstractParameterResultNode implements BooleanExpressionResultInterface
{
    /** @var bool */
    protected bool $value;

    /**
     * KeyParameterExpression constructor.
     * @param bool $value
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function get(): bool
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
