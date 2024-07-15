<?php
namespace CP\Filter\Tokens;

class IntVal extends UnaryExpression implements ASTLeafNode{
    public function __construct($value)
    {
        $this->value = (int) $value;
    }

    public function apply($data)
    {
        return $this->value;
    }
}
