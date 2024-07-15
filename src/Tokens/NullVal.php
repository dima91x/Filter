<?php
namespace CP\Filter\Tokens;

class NullVal implements ASTLeafNode{
    use ASTNodeTrait;

    public function reduce(callable $callback, $value)
    {
        return $callback($value, $this);
    }

    public function getValue(){
        return null;
    }

    public function getChildren(): array
    {
        return [];
    }

    public function getTypeName(): string
    {
        return "NULL";
    }

    public function apply($data){
        return null;
    }

}
