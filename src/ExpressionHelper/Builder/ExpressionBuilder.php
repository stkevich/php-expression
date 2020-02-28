<?php

declare(strict_types=1);

namespace StKevich\ExpressionHelper\Builder;


use StKevich\ExpressionTree\ExpressionInterface;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\AndExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\NotExpression;
use StKevich\ExpressionTree\ExpressionNodes\LogicExpressions\OrExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\AbstractParameterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\EqualExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\GreaterExpression;
use StKevich\ExpressionTree\ExpressionNodes\ParameterExpressions\LesserExpression;
use StKevich\ExpressionTree\ExpressionResult\Numbers\MultiplyExpressionResultInterface;
use StKevich\ExpressionTree\ExpressionResult\Types\BooleanExpressionResultInterface;
use StKevich\ExpressionTree\ParameterNodes\AbstractParameterNode;
use StKevich\ExpressionTree\ParameterNodes\BooleanNode;
use StKevich\ExpressionTree\ParameterNodes\FloatNode;
use StKevich\ExpressionTree\ParameterNodes\IntegerNode;
use StKevich\ExpressionTree\ParameterNodes\KeyNode;
use StKevich\ExpressionTree\ParameterNodes\StringNode;

class ExpressionBuilder
{

    public static function eq($node1, $node2): ExpressionInterface
    {
        return new EqualExpression(
            self::makeParameterFromNode($node1),
            self::makeParameterFromNode($node2),
        );
    }

    public static function gt($node1, $node2): ExpressionInterface
    {
        return new GreaterExpression(
            self::makeParameterFromNode($node1),
            self::makeParameterFromNode($node2),
        );
    }

    public static function lt($node1, $node2): ExpressionInterface
    {
        return new LesserExpression(
            self::makeParameterFromNode($node1),
            self::makeParameterFromNode($node2),
        );
    }

    public static function not($node): ExpressionInterface
    {
        return new NotExpression(
            self::makeBooleanExpressionFromNode($node),
        );
    }

    public static function and(...$nodes): ExpressionInterface
    {
        return new AndExpression(...$nodes);
    }

    public static function or(...$nodes): ExpressionInterface
    {
        return new OrExpression(...$nodes);
    }

    protected static function makeBooleanExpressionFromNode($node): BooleanExpressionResultInterface
    {
        switch (true) {
            case is_bool($node):
                return new BooleanNode($node);
                break;
            case is_string($node):
                $key = self::makeKey($node);
                if ($key instanceof KeyNode) {
                    return $key;
                }
                break;
            case $node instanceof BooleanExpressionResultInterface:
                return $node;
                break;
        }
        return new BooleanNode((bool)$node);
    }

    protected static function makeParameterFromNode($node): AbstractParameterNode
    {
        switch (true) {
            case is_int($node):
                return new IntegerNode($node);
                break;
            case is_float($node):
                return new FloatNode($node);
                break;
            case is_string($node):
                return self::makeKey($node);
                break;
            case is_bool($node):
                return new BooleanNode($node);
                break;
        }

        return $node;
    }

    protected static function makeKey(string $node)
    {
        if ($node[0] == '$') {
            $key = substr($node, 1);
            return new KeyNode($key);
        }
        else {
            return new StringNode($node);
        }
    }

}
