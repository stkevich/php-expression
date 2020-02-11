<?php

declare(strict_types=1);

namespace StKevich\Tests;

use PHPUnit\Framework\TestCase;
use StKevich\ExpressionHandler\PDO\PDOExpressionHandler;
use StKevich\ExpressionHandler\PDO\PDOExpressionResult;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\NotExpression;
use StKevich\ExpressionTree\Exceptions\ExpressionException;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AndExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\OrExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\EqualExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\GreaterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\LesserExpression;
use StKevich\ExpressionTree\ParameterNodes\FloatNode;
use StKevich\ExpressionTree\ParameterNodes\IntegerNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

class PDOExpressionHandlerTest extends TestCase
{
    /**
     * @param $expression
     * @param $expectedResult
     * @throws ExpressionException
     * @dataProvider expressionProvider
     * @dataProvider equalExpressionProvider
     * @dataProvider greaterExpressionProvider
     * @dataProvider lesserExpressionProvider
     * @dataProvider notExpressionProvider
     * @dataProvider andExpressionProvider
     * @dataProvider orExpressionProvider
     * @dataProvider complexityExpressionProvider
     */
    public function testHandleExpression($expression, $expectedResult)
    {
        $handler = new PDOExpressionHandler();
        $pdoExpression = $handler->handle($expression);
        $this->assertEquals($expectedResult, $pdoExpression);
    }

    /**
     * @throws ExpressionException
     */
    public function testUnexpectedExpression()
    {
        $this->expectException(ExpressionException::class);

        $unexpectedExpressionType = $this->createMock(ExpressionNodesInterface::class);

        $handler = new PDOExpressionHandler();
        $handler->handle($unexpectedExpressionType);
    }

    /**
     * @return array
     */
    public function expressionProvider()
    {
        return [
            'baseEqual' => [
                'expression' => new EqualExpression(
                    new KeyNode('name'),
                    new StringNode('Vova'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'name = :1',
                    [1 => 'Vova']
                ),
            ],
            'simpleNot' => [
                'expression' => new NotExpression(
                    new KeyNode('isMarried')
                ),
                'expectedResult' => new PDOExpressionResult(
                    'NOT(isMarried)',
                    []
                ),
            ],
        ];
    }

    public function equalExpressionProvider()
    {
        return [
            'equal_keyAndString' => [
                'expression' => new EqualExpression(
                    new KeyNode('key'),
                    new StringNode('string'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key = :1',
                    [1 => 'string']
                ),
            ],
            'equal_twoKey' => [
                'expression' => new EqualExpression(
                    new KeyNode('key1'),
                    new KeyNode('key2'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key1 = key2',
                    []
                ),
            ],
            'equal_twoString' => [
                'expression' => new EqualExpression(
                    new StringNode('string1'),
                    new StringNode('string2'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    ':1 = :2',
                    [1 => 'string1', 2 => 'string2']
                ),
            ],
        ];
    }

    public function greaterExpressionProvider()
    {
        return [
            'greater_keyAndInteger' => [
                'expression' => new GreaterExpression(
                    new KeyNode('key'),
                    new IntegerNode(1),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key > :1',
                    [1 => 1]
                ),
            ],
            'greater_twoKey' => [
                'expression' => new GreaterExpression(
                    new KeyNode('key1'),
                    new KeyNode('key2'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key1 > key2',
                    []
                ),
            ],
            'greater_twoInteger' => [
                'expression' => new GreaterExpression(
                    new IntegerNode(1),
                    new IntegerNode(2),
                ),
                'expectedResult' => new PDOExpressionResult(
                    ':1 > :2',
                    [1 => 1, 2 => 2]
                ),
            ],
        ];
    }

    public function lesserExpressionProvider()
    {
        return [
            'leaser_keyAndInteger' => [
                'expression' => new LesserExpression(
                    new KeyNode('key'),
                    new IntegerNode(1),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key < :1',
                    [1 => 1]
                ),
            ],
            'leaser_twoKey' => [
                'expression' => new LesserExpression(
                    new KeyNode('key1'),
                    new KeyNode('key2'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key1 < key2',
                    []
                ),
            ],
            'leaser_twoInteger' => [
                'expression' => new LesserExpression(
                    new IntegerNode(1),
                    new IntegerNode(2),
                ),
                'expectedResult' => new PDOExpressionResult(
                    ':1 < :2',
                    [1 => 1, 2 => 2]
                ),
            ],
        ];
    }

    public function notExpressionProvider()
    {
        return [
            'not_key' => [
                'expression' => new NotExpression(
                    new KeyNode('key'),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'NOT(key)',
                    []
                ),
            ],
            'not_expression' => [
                'expression' => new NotExpression(
                    new EqualExpression(
                        new KeyNode('key'),
                        new StringNode('string'),
                    ),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'NOT(key = :1)',
                    [1 => 'string']
                ),
            ],
        ];
    }

    public function andExpressionProvider()
    {
        return [
            'and_oneExpression' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('key'),
                        new StringNode('string'),
                    ),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key = :1',
                    [1 => 'string']
                ),
            ],
            'and_moreThanOneExpression' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('key1'),
                        new StringNode('string'),
                    ),
                    new LesserExpression(
                        new KeyNode('key2'),
                        new IntegerNode(1),
                    ),
                    new LesserExpression(
                        new KeyNode('key3'),
                        new FloatNode(1.1),
                    ),
                ),
                'expectedResult' => new PDOExpressionResult(
                    "key1 = :1 AND key2 < :2 AND key3 < :3",
                    [1 => 'string', 2 => 1, 3 => 1.1]
                ),
            ],
        ];
    }

    public function orExpressionProvider()
    {
        return [
            'or_oneExpression' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('key'),
                        new StringNode('string'),
                    ),
                ),
                'expectedResult' => new PDOExpressionResult(
                    'key = :1',
                    [1 => 'string']
                ),
            ],
            'or_moreThanOneExpression' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('key1'),
                        new StringNode('string'),
                    ),
                    new LesserExpression(
                        new KeyNode('key2'),
                        new IntegerNode(1),
                    ),
                    new LesserExpression(
                        new KeyNode('key3'),
                        new FloatNode(1.1),
                    ),
                ),
                'expectedResult' => new PDOExpressionResult(
                    "key1 = :1 OR key2 < :2 OR key3 < :3",
                    [1 => 'string', 2 => 1, 3 => 1.1]
                ),
            ],
        ];
    }

    public function complexityExpressionProvider()
    {
        return [
            'complexityExpression' => [
                'expression' => new OrExpression(
                    new AndExpression(
                        new EqualExpression(
                            new KeyNode('key1'),
                            new StringNode('string'),
                        ),
                        new LesserExpression(
                            new KeyNode('key2'),
                            new IntegerNode(1),
                        ),
                    ),
                    new LesserExpression(
                        new KeyNode('key3'),
                        new FloatNode(1.1),
                    ),
                ),
                'expectedResult' => new PDOExpressionResult(
                    "(key1 = :1 AND key2 < :2) OR key3 < :3",
                    [1 => 'string', 2 => 1, 3 => 1.1]
                ),
            ],
        ];
    }

}
