<?php
namespace CP\Filter\Tokens;

/*
Интерфейс узлов AST дерева
 */
interface ASTNode{
    public function getParent();
    public function setParent( ASTNode $parent_node );
    public function getChildren(): array;

    /**
      Метод использует массив с данными в data как данные полей объекта и применяет к данному объекту выражение фильра.

      Прим:
         filter="eq(FieldName, 1)"
         $ast->apply(['FieldName' => 1]) будет true
         $ast->apply(['FieldName' => 2]) будет false

     */
    public function apply(array $data);
}
