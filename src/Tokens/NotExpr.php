<?php
namespace CP\Filter\Tokens;

class NotExpr extends UnaryExpression{
    public function __construct(ASTNode $sub_expr)
    {
        $this->value = $sub_expr;
        $sub_expr->setParent( $this );
    }

    public function getChildren(): array{
        return [ $this->value ];
    }

    public function reduce(callable $callback, $value){
        $value = $callback($value, $this);
        return $this->value->reduce($callback, $value);
    }

    public function replaceChild(ASTNode $replace, ASTNode $new_node){
        $this->value = $new_node;
        $new_node->setParent( $this );
    }

    public function apply($data)
    {
        return ! $this->value->apply($data);
    }
}
