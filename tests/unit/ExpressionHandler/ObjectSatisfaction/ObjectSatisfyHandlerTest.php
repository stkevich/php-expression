<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler\Tests;

use PHPUnit\Framework\TestCase;
use StKevich\ExpressionHandler\ObjectSatisfaction\ObjectSatisfyHandler;
use StKevich\ExpressionTree\Exceptions\ExpressionException;
use StKevich\ExpressionTree\ExpressionNodes\ExpressionNodesInterface;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AndExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\NotExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\OrExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\EqualExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\GreaterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\LesserExpression;
use StKevich\ExpressionTree\ParameterNodes\FloatNode;
use StKevich\ExpressionTree\ParameterNodes\IntegerNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

class ObjectSatisfyHandlerTest extends TestCase
{
    /**
     * @param $expression
     * @param $object
     * @param $expectedResult
     * @throws ExpressionException
     * @dataProvider equalExpressionProvider
     * @dataProvider equalExpressionSoftTypesProvider
     * @dataProvider greaterExpressionProvider
     * @dataProvider lesserExpressionProvider
     * @dataProvider notExpressionProvider
     * @dataProvider andExpressionProvider
     * @dataProvider orExpressionProvider
     * @dataProvider complexityExpressionProvider
     */
    public function testHandleExpression($expression, $object, $expectedResult)
    {
        $handler = new ObjectSatisfyHandler($expression);
        $result = $handler->handle($object);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @throws ExpressionException
     */
    public function testUnexpectedExpression_ThrowException()
    {
        $this->expectException(ExpressionException::class);

        $unexpectedExpressionType = $this->createMock(ExpressionNodesInterface::class);
        $object = $this->getTestedObject();

        $handler = new ObjectSatisfyHandler($unexpectedExpressionType);
        $handler->handle($object);
    }

    /**
     * @throws ExpressionException
     */
    public function testUnexpectedObjectValue_ThrowException()
    {
        $this->expectException(ExpressionException::class);

        $expression = new EqualExpression(
            new KeyNode('unexpectedParam'),
            new StringNode('stringValue'),
        );

        $object = $this->getTestedObject();

        $handler = new ObjectSatisfyHandler($expression);
        $handler->handle($object);
    }

    protected function getTestedObject()
    {
        return (object) [
            'stringParam1' => 'stringValue',
            'intParam1' => 30,
            'floatParam1' => 3.14,
            'booleanParam1' => true,
            'floatParam2' => 3.14
        ];
    }

    /**
     * @return array
     */
    public function equalExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'equal_keyAndString_positive' => [
                'expression' => new EqualExpression(
                    new KeyNode('stringParam1'),
                    new StringNode('stringValue'),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'equal_keyAndString_negative' => [
                'expression' => new EqualExpression(
                    new KeyNode('stringParam1'),
                    new StringNode('incorrectStringValue'),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'equal_twoStrings_positive' => [
                'expression' => new EqualExpression(
                    new StringNode('stringValue'),
                    new StringNode('stringValue'),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'equal_twoStrings_negative' => [
                'expression' => new EqualExpression(
                    new StringNode('stringValue'),
                    new StringNode('incorrectStringValue'),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'equal_twoKey_positive' => [
                'expression' => new EqualExpression(
                    new KeyNode('floatParam1'),
                    new KeyNode('floatParam2'),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'equal_twoKey_negative' => [
                'expression' => new EqualExpression(
                    new KeyNode('intParam1'),
                    new KeyNode('floatParam2'),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function equalExpressionSoftTypesProvider()
    {
        $object = $this->getTestedObject();
        return [
            'equal_stringAndInteger_positive' => [
                'expression' => new EqualExpression(
                    new StringNode('1'),
                    new IntegerNode(1),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'equal_stringAndInteger_negative' => [
                'expression' => new EqualExpression(
                    new StringNode('2'),
                    new IntegerNode(1),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'equal_stringAndFloat_positive' => [
                'expression' => new EqualExpression(
                    new StringNode('1.1'),
                    new FloatNode(1.1),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'equal_stringAndFloat_negative' => [
                'expression' => new EqualExpression(
                    new StringNode('1.1'),
                    new FloatNode(1.2),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    public function greaterExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'greater_keyAndInteger_positive' => [
                'expression' => new GreaterExpression(
                    new KeyNode('intParam1'),
                    new IntegerNode(29),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'greater_keyAndInteger_negative' => [
                'expression' => new GreaterExpression(
                    new KeyNode('floatParam1'),
                    new FloatNode(3.2),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'greater_twoInteger_positive' => [
                'expression' => new GreaterExpression(
                    new IntegerNode(1),
                    new IntegerNode(0),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'greater_twoInteger_negative' => [
                'expression' => new GreaterExpression(
                    new IntegerNode(0),
                    new IntegerNode(1),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'greater_twoKey_positive' => [
                'expression' => new GreaterExpression(
                    new KeyNode('intParam1'),
                    new KeyNode('floatParam1'),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'greater_twoKey_negative' => [
                'expression' => new GreaterExpression(
                    new KeyNode('floatParam1'),
                    new KeyNode('intParam1'),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function lesserExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'lesser_keyAndInteger_positive' => [
                'expression' => new LesserExpression(
                    new KeyNode('intParam1'),
                    new IntegerNode(31),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'lesser_keyAndInteger_negative' => [
                'expression' => new LesserExpression(
                    new KeyNode('floatParam1'),
                    new FloatNode(3.1),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'lesser_twoInteger_positive' => [
                'expression' => new LesserExpression(
                    new IntegerNode(0),
                    new IntegerNode(1),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'lesser_twoInteger_negative' => [
                'expression' => new LesserExpression(
                    new IntegerNode(1),
                    new IntegerNode(0),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'lesser_twoKey_positive' => [
                'expression' => new LesserExpression(
                    new KeyNode('floatParam1'),
                    new KeyNode('intParam1'),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'lesser_twoKey_negative' => [
                'expression' => new LesserExpression(
                    new KeyNode('intParam1'),
                    new KeyNode('floatParam1'),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function notExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'not_booleanKey_negative' => [
                'expression' => new NotExpression(
                    new KeyNode('booleanParam1'),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'not_failedExpression_positive' => [
                'expression' => new NotExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'not_successExpression_negative' => [
                'expression' => new NotExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('stringValue'),
                    ),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function andExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'and_soloExpression_positive' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('stringValue'),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'and_soloExpression_negative' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'and_moreThanOneExpressionAllCorrect_positive' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('stringValue'),
                    ),
                    new GreaterExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(20),
                    ),
                    new LesserExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(40),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'and_moreThanOneExpressionOneIncorrect_negative' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                    new GreaterExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(20),
                    ),
                    new LesserExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(40),
                    ),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'and_moreThanOneExpressionAllIncorrect_negative' => [
                'expression' => new AndExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                    new GreaterExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(35),
                    ),
                    new LesserExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(25),
                    ),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function orExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'or_soloExpression_positive' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('stringValue'),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'or_soloExpression_negative' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
            'or_moreThanOneExpressionAllCorrect_positive' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('stringValue'),
                    ),
                    new GreaterExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(20),
                    ),
                    new LesserExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(40),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'or_moreThanOneExpressionOneIncorrect_positive' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                    new GreaterExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(20),
                    ),
                    new LesserExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(40),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
            'or_moreThanOneExpressionAllIncorrect_negative' => [
                'expression' => new OrExpression(
                    new EqualExpression(
                        new KeyNode('stringParam1'),
                        new StringNode('incorrectStringValue'),
                    ),
                    new GreaterExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(35),
                    ),
                    new LesserExpression(
                        new KeyNode('intParam1'),
                        new IntegerNode(25),
                    ),
                ),
                'object' => $object,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @return array
     */
    public function complexityExpressionProvider()
    {
        $object = $this->getTestedObject();
        return [
            'complexityExpression_positive' => [
                'expression' => new AndExpression(
                    new OrExpression(
                        new EqualExpression(
                            new KeyNode('stringParam1'),
                            new StringNode('incorrectStringValue'),
                        ),
                        new EqualExpression(
                            new KeyNode('stringParam1'),
                            new StringNode('stringValue'),
                        ),
                    ),
                    new OrExpression(
                        new GreaterExpression(
                            new KeyNode('intParam1'),
                            new IntegerNode(18),
                        ),
                        new LesserExpression(
                            new KeyNode('intParam1'),
                            new IntegerNode(65),
                        ),
                    ),
                ),
                'object' => $object,
                'expectedResult' => true,
            ],
        ];
    }

}
