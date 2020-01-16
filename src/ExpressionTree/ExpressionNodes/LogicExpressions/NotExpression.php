<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\LogicExpressions;

use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;

class NotExpression extends AbstractLogicExpression implements BooleanExpressionResultInterface, SingleExpressionResultInterface
{
    /** @var BooleanExpressionResultInterface */
    protected BooleanExpressionResultInterface $parameter;

    /**
     * EqualExpression constructor.
     * @param BooleanExpressionResultInterface $parameter
     */
    public function __construct(BooleanExpressionResultInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @return BooleanExpressionResultInterface
     */
    public function getInternalExpression(): BooleanExpressionResultInterface
    {
        return $this->parameter;
    }

    /**
     * @param $value
     * @return bool
     */
    public function implementsFunction($value): bool
    {
        return !(bool)$value;
    }

    /**
     * @param string|null $viewType
     * @return string
     */
    public function standardGlue(string $viewType = null): string
    {
        switch ($viewType) {
            default:
                return 'NOT';
                break;
        }
    }

}
