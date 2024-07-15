<?php
namespace CP\Filter\Tokens;

/*
  Все вырежения работающие с двумя операндами: and, or, ==.
 */
abstract class BinaryExpression implements ASTNode
{
    protected $parent;
    protected $left;
    protected $right;

    public function __construct(ASTNode $left, ASTNode $right)
    {
        $this->left = $left;
        $this->right = $right;

        $left->setParent($this);
        $right->setParent($this);
    }

    public function getParent(): ASTNode
    {
        return $this->parent;
    }

    public function setParent(ASTNode $parent)
    {
        $this->parent = $parent;
    }

    public function getChildren(): array
    {
        return [ $this->left, $this->right ];
    }
}
