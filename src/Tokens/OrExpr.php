<?php
namespace CP\Filter\Tokens;

class OrExpr extends BinaryExpression{

    public function apply($data)
    {
        return $this->left->apply($data) || $this->right->apply($data);
    }

}
