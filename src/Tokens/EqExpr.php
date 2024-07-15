<?php
namespace CP\Filter\Tokens;

class EqExpr extends BinaryExpression{

    public function apply($data)
    {
        $left = $this->left->apply($data);
        $right = $this->right->apply($data);

        return $left === $right;
    }

}
