<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;

class BooleanNode extends AbstractParameterNode implements BooleanExpressionResultInterface
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
     * @return bool
     */
    public function exec()
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
