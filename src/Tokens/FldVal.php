<?php
namespace CP\Filter\Tokens;


class FldVal extends UnaryExpression implements ASTLeafNode{

    public function __construct( $field ){
        $this->value = $field;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getChildren(): array{
        return [];
    }

    public function apply($data)
    {
        return $data[$this->value];
    }

}
