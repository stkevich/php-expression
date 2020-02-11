<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ParameterNodes;

use StKevich\ExpressionTree\Exceptions\ExpressionException;
use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\NumericalExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\StringExpressionResultInterface;

class KeyNode extends AbstractParameterNode implements
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
     * @throws ExpressionException
     */
    public function exec()
    {
        throw new ExpressionException(sprintf('Key node %s is not define', $this->get()));
    }

    /**
     * @param AbstractParameterNode $parameter
     * @return bool
     * @throws ExpressionException
     */
    public function is(AbstractParameterNode $parameter): bool
    {
        throw new ExpressionException(sprintf('Key node %s can\'t be compare', $this->get()));
    }
}
