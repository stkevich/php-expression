<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions;

use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;

class GreaterExpression extends AbstractParameterExpressionResult implements BooleanExpressionResultInterface
{
    /**
     * @param mixed $valueLeft
     * @param mixed $valueRight
     * @return bool
     */
    public function implementsFunction($valueLeft, $valueRight): bool
    {
        return $valueLeft > $valueRight;
    }

    /**
     * @param string|null $viewType
     * @return string
     */
    public function standardGlue(string $viewType = null): string
    {
        switch ($viewType) {
            default:
                return '>';
                break;
        }
    }

}
