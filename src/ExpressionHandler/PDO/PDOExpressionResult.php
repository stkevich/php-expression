<?php

declare(strict_types=1);

namespace StKevich\ExpressionHandler\PDO;

class PDOExpressionResult
{
    /** @var string */
    protected string $expression;

    /** @var array */
    protected array $data;

    /**
     * PDOExpressionResult constructor.
     * @param string $expression
     * @param array $values
     */
    public function __construct(string $expression, array $values = [])
    {
        $this->data = $values;
        $this->expression = $expression;
    }

    /**
     * @return string|null
     */
    public function getExpressionString(): ?string
    {
        return $this->expression;
    }

    /**
     * @return array
     */
    public function getData(): ?array
    {
        return $this->data;
    }


}