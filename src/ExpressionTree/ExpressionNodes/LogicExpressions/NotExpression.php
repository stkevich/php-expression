<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\LogicExpressions;

use StKevich\ExpressionTree\ExpressionResult\Numbers\SingleExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;

class NotExpression extends AbstractLogicExpression implements BooleanExpressionResultInterface, SingleExpressionResultInterface
{
    /** @var BooleanExpressionResultInterface */
    protected BooleanExpressionResultInterface $expression;

    /**
     * NotExpression constructor.
     * @param BooleanExpressionResultInterface $expression
     */
    public function __construct(BooleanExpressionResultInterface $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return BooleanExpressionResultInterface
     */
    public function getInternalExpression(): BooleanExpressionResultInterface
    {
        return $this->expression;
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
     * @return bool
     */
    public function exec()
    {
        return !(bool)$this->expression->exec();
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
