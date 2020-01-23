<?php

declare(strict_types=1);

namespace StKevich\Tests;

use PHPUnit\Framework\TestCase;
use StKevich\ExpressionHandler\SQL\SQLExpressionHandler;
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

class SQLExpressionHandlerTest extends TestCase
{
    /**
     * @param $expression
     * @param $expectedSql
     * @throws ExpressionException
     * @dataProvider equalExpressionProvider
     * @dataProvider greaterExpressionProvider
     * @dataProvider lesserExpressionProvider
     * @dataProvider notExpressionProvider
     * @dataProvider andExpressionProvider
     * @dataProvider orExpressionProvider
     * @dataProvider complexityExpressionProvider
     */
    public function testExpression($expression, $expectedSql)
    {
        $parser = new SQLExpressionHandler();
        $sql = $parser->handle($expression);

        $this->assertEquals($expectedSql, $sql);
    }

    public function equalExpressionProvider()
    {
        return [
            'equal_keyAndString' => [
                'expression' => new EqualExpression(
                    new KeyNode('key'),
                    new StringNode('string'),
                ),
                'sql' => "key = 'string'",
            ],
            'equal_twoKey' => [
                'expression' => new EqualExpression(
                    new KeyNode('key1'),
                    new KeyNode('key2'),
                ),
                'sql' => "key1 = key2",
            ],
            'equal_twoString' => [
                'expression' => new EqualExpression(
                    new StringNode('string1'),
                    new StringNode('string2'),
                ),
                'sql' => "'string1' = 'string2'",
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
                'sql' => "key > 1",
            ],
            'greater_twoKey' => [
                'expression' => new GreaterExpression(
                    new KeyNode('key1'),
                    new KeyNode('key2'),
                ),
                'sql' => "key1 > key2",
            ],
            'greater_twoInteger' => [
                'expression' => new GreaterExpression(
                    new IntegerNode(1),
                    new IntegerNode(2),
                ),
                'sql' => "1 > 2",
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
                'sql' => "key < 1",
            ],
            'leaser_twoKey' => [
                'expression' => new LesserExpression(
                    new KeyNode('key1'),
                    new KeyNode('key2'),
                ),
                'sql' => "key1 < key2",
            ],
            'leaser_twoInteger' => [
                'expression' => new LesserExpression(
                    new IntegerNode(1),
                    new IntegerNode(2),
                ),
                'sql' => "1 < 2",
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
                'sql' => "NOT(key)",
            ],
            'not_expression' => [
                'expression' => new NotExpression(
                    new EqualExpression(
                        new KeyNode('key'),
                        new StringNode('string'),
                    ),
                ),
                'sql' => "NOT(key = 'string')",
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
                'sql' => "key = 'string'",
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
                'sql' => "key1 = 'string' AND key2 < 1 AND key3 < 1.1",
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
                'sql' => "key = 'string'",
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
                'sql' => "key1 = 'string' OR key2 < 1 OR key3 < 1.1",
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
                'sql' => "(key1 = 'string' AND key2 < 1) OR key3 < 1.1",
            ],
        ];
    }

}
