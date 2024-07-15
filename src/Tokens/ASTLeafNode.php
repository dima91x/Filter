<?php

namespace CP\Filter\Tokens;

/*
 * Лист AST дерева
 */

interface ASTLeafNode extends ASTNode{
    public function getValue();
}
