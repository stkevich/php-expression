<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions;

use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;

class EqualExpression extends AbstractParameterExpression implements BooleanExpressionResultInterface
{
    /**
     * @return bool
     */
    public function exec()
    {
        return $this->getLeftExpression()->exec() == $this->getRightExpression()->exec();
    }

    /**
     * @param string|null $viewType
     * @return string
     */
    public function standardGlue(string $viewType = null): string
    {
        switch ($viewType) {
            default:
                return '=';
                break;
        }
    }
}
