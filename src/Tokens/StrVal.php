<?php
namespace CP\Filter\Tokens;

class StrVal extends UnaryExpression implements ASTLeafNode
{
    public function __construct($value)
    {
        /* first and last symbol of $value is '"' or "'" */
        $this->value = mb_substr($value, 1, -1);
    }

    public function getTypeName(): string
    {
        return "String";
    }

    public function getChildren(): array{
        return [];
    }

    public function apply($data)
    {
        return $this->value;
    }

}
