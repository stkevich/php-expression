<?php

declare(strict_types=1);

namespace StKevich\Tests;

use PHPUnit\Framework\TestCase;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AbstractLogicExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AndExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\NotExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\OrExpression;
use StKevich\ExpressionTree\ParameterNodes\BooleanNode;

class LogicExpressionTest extends TestCase
{
    /**
     * @param AbstractLogicExpression $expression
     * @param mixed $expectedResult
     * @dataProvider andExpressionProvider
     * @dataProvider orExpressionProvider
     * @dataProvider notExpressionProvider
     */
    public function testExpressionExec(AbstractLogicExpression $expression, $expectedResult)
    {
        $result = $expression->exec();
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function andExpressionProvider()
    {
        return [
            'andExpression_EmptyParams' => [
                'expression' => new AndExpression(),
                'expectedResult' => true
            ],
            'andExpression_OneTrueParams' => [
                'expression' => new AndExpression(
                    new BooleanNode(true),
                ),
                'expectedResult' => true
            ],
            'andExpression_ManyTrueParams' => [
                'expression' => new AndExpression(
                    new BooleanNode(true),
                    new BooleanNode(true),
                    new BooleanNode(true),
                ),
                'expectedResult' => true
            ],
            'andExpression_OneFalseParams' => [
                'expression' => new AndExpression(
                    new BooleanNode(false),
                ),
                'expectedResult' => false
            ],
            'andExpression_OneFalseAndManyTrueParams' => [
                'expression' => new AndExpression(
                    new BooleanNode(false),
                    new BooleanNode(true),
                    new BooleanNode(true),
                ),
                'expectedResult' => false
            ],
        ];
    }

    /**
     * @return array
     */
    public function orExpressionProvider()
    {
        return [
            'orExpression_EmptyParams' => [
                'expression' => new OrExpression(),
                'expectedResult' => false
            ],
            'orExpression_OneTrueParams' => [
                'expression' => new OrExpression(
                    new BooleanNode(true),
                ),
                'expectedResult' => true
            ],
            'orExpression_ManyTrueParams' => [
                'expression' => new OrExpression(
                    new BooleanNode(true),
                    new BooleanNode(true),
                    new BooleanNode(true),
                ),
                'expectedResult' => true
            ],
            'orExpression_OneFalseParams' => [
                'expression' => new OrExpression(
                    new BooleanNode(false),
                ),
                'expectedResult' => false
            ],
            'orExpression_ManyFalseParams' => [
                'expression' => new OrExpression(
                    new BooleanNode(false),
                    new BooleanNode(false),
                    new BooleanNode(false),
                ),
                'expectedResult' => false
            ],
            'orExpression_OneFalseAndManyTrueParams' => [
                'expression' => new OrExpression(
                    new BooleanNode(false),
                    new BooleanNode(true),
                    new BooleanNode(true),
                ),
                'expectedResult' => true
            ],
        ];
    }

    /**
     * @return array
     */
    public function notExpressionProvider()
    {
        return [
            'notExpression_TrueParams' => [
                'expression' => new NotExpression(
                    new BooleanNode(true),
                ),
                'expectedResult' => false
            ],
            'notExpression_FalseParams' => [
                'expression' => new NotExpression(
                    new BooleanNode(false),
                ),
                'expectedResult' => true
            ],
        ];
    }


}
