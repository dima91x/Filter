<?php
namespace CP\Filter\Tokens;

/*
  Общий код для операций с одним операндом.
  Все текрминалы также можно рассматривать как операции с одним операндом.
   За исключением NOT все унарные выражения являются терминалами
 */

abstract class UnaryExpression implements ASTNode {
    protected $value;

    public function getParent(): ASTNode
    {
        return $this->parent;
    }

    public function setParent(ASTNode $parent)
    {
        $this->parent = $parent;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getChildren(): array // override in "NOT"
    {
        return [];
    }

}
