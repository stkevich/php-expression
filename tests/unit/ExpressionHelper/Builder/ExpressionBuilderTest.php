<?php

declare(strict_types=1);

namespace StKevich\Tests;


use PHPUnit\Framework\TestCase;
use StKevich\ExpressionHelper\Builder\ExpressionBuilder AS EB;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AndExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\NotExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\OrExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\EqualExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\GreaterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\LesserExpression;
use StKevich\ExpressionTree\ParameterNodes\BooleanNode;
use StKevich\ExpressionTree\ParameterNodes\FloatNode;
use StKevich\ExpressionTree\ParameterNodes\IntegerNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

class ExpressionBuilderTest extends TestCase
{

    /**
     * @param $expressionFromBuilder
     * @param $expressionExpected
     * @dataProvider equalExpressionProvider
     * @dataProvider greaterExpressionProvider
     * @dataProvider lesserExpressionProvider
     * @dataProvider notExpressionProvider
     * @dataProvider andExpressionProvider
     * @dataProvider orExpressionProvider
     */
    public function testBuild($expressionExpected, $expressionFromBuilder)
    {
        $this->assertEquals($expressionExpected, $expressionFromBuilder);
    }

    public function equalExpressionProvider(): array
    {
        return [
            'equal_keyAndString' => [
                'expressionExpected' => new EqualExpression(
                    new KeyNode('stringParam'),
                    new StringNode('stringValue'),
                ),
                'expressionFromBuilder' => EB::eq('$stringParam', 'stringValue'),
            ],
            'equal_stringAndInt' => [
                'expressionExpected' => new EqualExpression(
                    new StringNode('stringValue'),
                    new IntegerNode(1),
                ),
                'expressionFromBuilder' => EB::eq('stringValue', 1),
            ],
            'equal_keyAndKey' => [
                'expressionExpected' => new EqualExpression(
                    new KeyNode('stringParam1'),
                    new KeyNode('stringParam2'),
                ),
                'expressionFromBuilder' => EB::eq('$stringParam1', '$stringParam2'),
            ],
        ];
    }

    public function greaterExpressionProvider(): array
    {
        return [
            'greater_keyAndInt' => [
                'expressionExpected' => new GreaterExpression(
                    new KeyNode('intParam'),
                    new IntegerNode(1),
                ),
                'expressionFromBuilder' => EB::gt('$intParam', 1),
            ],
            'greater_floatAndInt' => [
                'expressionExpected' => new GreaterExpression(
                    new FloatNode(1.1),
                    new IntegerNode(1),
                ),
                'expressionFromBuilder' => EB::gt(1.1, 1),
            ],
            'greater_keyAndKey' => [
                'expressionExpected' => new GreaterExpression(
                    new KeyNode('intParam1'),
                    new KeyNode('floatParam1'),
                ),
                'expressionFromBuilder' => EB::gt('$intParam1', '$floatParam1'),
            ],
        ];
    }

    public function lesserExpressionProvider(): array
    {
        return [
            'lesser_keyAndInt' => [
                'expressionExpected' => new LesserExpression(
                    new KeyNode('intParam'),
                    new IntegerNode(1),
                ),
                'expressionFromBuilder' => EB::lt('$intParam', 1),
            ],
            'lesser_floatAndInt' => [
                'expressionExpected' => new LesserExpression(
                    new FloatNode(1.1),
                    new IntegerNode(1),
                ),
                'expressionFromBuilder' => EB::lt(1.1, 1),
            ],
            'lesser_keyAndKey' => [
                'expressionExpected' => new LesserExpression(
                    new KeyNode('intParam1'),
                    new KeyNode('floatParam1'),
                ),
                'expressionFromBuilder' => EB::lt('$intParam1', '$floatParam1'),
            ],
        ];
    }

    public function notExpressionProvider(): array
    {
        return [
            'not_bool' => [
                'expressionExpected' => new NotExpression(
                    new BooleanNode(true),
                ),
                'expressionFromBuilder' => EB::not(true),
            ],
            'not_key' => [
                'expressionExpected' => new NotExpression(
                    new KeyNode('boolParam')
                ),
                'expressionFromBuilder' => EB::not('$boolParam'),
            ],
            'not_parameterExpression' => [
                'expressionExpected' => new NotExpression(
                    new EqualExpression(
                        new KeyNode('stringParam'),
                        new StringNode('stringValue'),
                    )
                ),
                'expressionFromBuilder' => EB::not(
                    EB::eq('$stringParam', 'stringValue')
                ),
            ],
        ];
    }

    public function andExpressionProvider(): array
    {
        return [
            'and_soloExpression' => [
                'expressionExpected' => new AndExpression(
                    new EqualExpression(
                        new IntegerNode(1),
                        new IntegerNode(2)
                    )
                ),
                'expressionFromBuilder' => EB::and(
                    EB::eq(1,2)
                ),
            ],
            'and_moreThanOneExpression' => [
                'expressionExpected' => new AndExpression(
                    new EqualExpression(
                        new IntegerNode(1),
                        new IntegerNode(2)
                    ),
                    new GreaterExpression(
                        new IntegerNode(3),
                        new IntegerNode(4)
                    ),
                    new LesserExpression(
                        new IntegerNode(5),
                        new IntegerNode(6)
                    )
                ),
                'expressionFromBuilder' => EB::and(
                    EB::eq(1,2),
                    EB::gt(3,4),
                    EB::lt(5,6)
                ),
            ],
        ];
    }

    public function orExpressionProvider(): array
    {
        return [
            'or_soloExpression' => [
                'expressionExpected' => new OrExpression(
                    new EqualExpression(
                        new IntegerNode(1),
                        new IntegerNode(2)
                    )
                ),
                'expressionFromBuilder' => EB::or(
                    EB::eq(1,2)
                ),
            ],
            'or_moreThanOneExpression' => [
                'expressionExpected' => new OrExpression(
                    new EqualExpression(
                        new IntegerNode(1),
                        new IntegerNode(2)
                    ),
                    new GreaterExpression(
                        new IntegerNode(3),
                        new IntegerNode(4)
                    ),
                    new LesserExpression(
                        new IntegerNode(5),
                        new IntegerNode(6)
                    )
                ),
                'expressionFromBuilder' => EB::or(
                    EB::eq(1,2),
                    EB::gt(3,4),
                    EB::lt(5,6)
                ),
            ],
        ];
    }


}
