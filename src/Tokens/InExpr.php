<?php

namespace CP\Filter\Tokens;

class InExpr extends BinaryExpression
{

    public function apply($data)
    {
        return str_contains($this->left->apply($data), $this->right->apply($data));
    }
}
