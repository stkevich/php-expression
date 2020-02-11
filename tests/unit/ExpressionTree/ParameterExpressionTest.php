<?php

declare(strict_types=1);

namespace StKevich\Tests;

use PHPUnit\Framework\TestCase;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\AbstractParameterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\EqualExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\GreaterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\LesserExpression;
use StKevich\ExpressionTree\ParameterNodes\FloatNode;
use StKevich\ExpressionTree\ParameterNodes\IntegerNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

class ParameterExpressionTest extends TestCase
{
    /**
     * @param AbstractParameterExpression $expression
     * @param $expectedResult
     * @dataProvider equalExpressionProvider
     * @dataProvider greaterExpressionProvider
     * @dataProvider leaserExpressionProvider
     */
    public function testExpressionExec(AbstractParameterExpression $expression, $expectedResult)
    {
        $result = $expression->exec();
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function equalExpressionProvider()
    {
        return [
            'equalExpression_equalStringParams' => [
                'expression' => new EqualExpression(
                    new StringNode('string'),
                    new StringNode('string'),
                ),
                'expectedResult' => true
            ],
            'equalExpression_notEqualStringParams' => [
                'expression' => new EqualExpression(
                    new StringNode('string'),
                    new StringNode('notString'),
                ),
                'expectedResult' => false
            ],
            'equalExpression_equalStringAndFloatParams' => [
                'expression' => new EqualExpression(
                    new StringNode('1.1'),
                    new FloatNode(1.1),
                ),
                'expectedResult' => true
            ],
            'equalExpression_equalFloatAndIntegerParams' => [
                'expression' => new EqualExpression(
                    new FloatNode(1),
                    new IntegerNode(1),
                ),
                'expectedResult' => true
            ],
            'equalExpression_notEqualFloatAndStringParams' => [
                'expression' => new EqualExpression(
                    new StringNode('1.1'),
                    new FloatNode(1),
                ),
                'expectedResult' => false
            ],
        ];
    }

    /**
     * @return array
     */
    public function greaterExpressionProvider()
    {
        return [
            'greaterExpression_LeftIntegerParamGreaterThanRightIntegerParam' => [
                'expression' => new GreaterExpression(
                    new IntegerNode(2),
                    new IntegerNode(1),
                ),
                'expectedResult' => true
            ],
            'greaterExpression_LeftIntegerParamLeaserThanRightIntegerParam' => [
                'expression' => new GreaterExpression(
                    new IntegerNode(1),
                    new IntegerNode(2),
                ),
                'expectedResult' => false
            ],
            'greaterExpression_LeftFloatParamGreaterThanRightIntegerParam' => [
                'expression' => new GreaterExpression(
                    new FloatNode(2.2),
                    new IntegerNode(1),
                ),
                'expectedResult' => true
            ],
            'greaterExpression_LeftFloatParamLeaserThanRightIntegerParam' => [
                'expression' => new GreaterExpression(
                    new FloatNode(1.1),
                    new IntegerNode(2),
                ),
                'expectedResult' => false
            ],
            'greaterExpression_LeftStringParamLeaserThanRightIntegerParam' => [
                'expression' => new GreaterExpression(
                    new StringNode('1'),
                    new IntegerNode(2),
                ),
                'expectedResult' => false
            ],
        ];
    }

    /**
     * @return array
     */
    public function leaserExpressionProvider()
    {
        return [
            'leaserExpression_LeftIntegerParamGreaterThanRightIntegerParam' => [
                'expression' => new LesserExpression(
                    new IntegerNode(2),
                    new IntegerNode(1),
                ),
                'expectedResult' => false
            ],
            'leaserExpression_LeftIntegerParamLeaserThanRightIntegerParam' => [
                'expression' => new LesserExpression(
                    new IntegerNode(1),
                    new IntegerNode(2),
                ),
                'expectedResult' => true
            ],
            'leaserExpression_LeftFloatParamGreaterThanRightIntegerParam' => [
                'expression' => new LesserExpression(
                    new FloatNode(2.2),
                    new IntegerNode(1),
                ),
                'expectedResult' => false
            ],
            'leaserExpression_LeftFloatParamLeaserThanRightIntegerParam' => [
                'expression' => new LesserExpression(
                    new FloatNode(1.1),
                    new IntegerNode(2),
                ),
                'expectedResult' => true
            ],
            'leaserExpression_LeftStringParamLeaserThanRightIntegerParam' => [
                'expression' => new LesserExpression(
                    new StringNode('1'),
                    new IntegerNode(2),
                ),
                'expectedResult' => true
            ],
        ];
    }

}
