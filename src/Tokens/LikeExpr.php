<?php

namespace CP\Filter\Tokens;

use CP\Filter\Exceptions\BadFieldExpression;
use CP\Filter\Exceptions\UnknownField;
use CP\Filter\Exceptions\WrongFieldType;

class LikeExpr extends BinaryExpression
{
    public function __construct(FldVal $left, StrVal $right)
    {
        parent::__construct($left, $right);
    }

    private function verifyFieldName($field)
    {
        $fieldName = $field->getValue();
        if (!preg_match('/^[A-Z,a-z]+[^\s]*/', $fieldName))
            throw new BadFieldExpression("Invalid field name");

        return $fieldName;
    }

    private function verifyFieldValue($field)
    {
        if (gettype($field) !== "string")
            throw new WrongFieldType("Field type not string");

        return $field;
    }

    private function verifyFilter($filter)
    {
        if (gettype($filter) !== "string")
            throw new WrongFieldType("Filter not string");

        if ($filter === "")
            throw new WrongFieldType("Filter is empty");

        return $filter;
    }

    public function apply($data)
    {
        $field = $this->left;
        $fieldName = $this->verifyFieldName($field);
        if(!isset($data[$fieldName]))
            throw new UnknownField([$fieldName]);

        $field = $this->left->apply($data);
        $field = $this->verifyFieldValue($field);

        $filter = $this->right->apply($data);
        $filter = $this->verifyFilter($filter);

        // экранирование спец символа "_" в фильтре
        $index = strpos($filter, '_');
        while ($index !== false) {
            if ($index == 0 || $filter[$index - 1] != "\\") {
                $begin = mb_substr($filter, 0, $index);
                $end = mb_substr($filter, $index + 1, NULL);
                $filter = $begin . '[\S]' . $end;
            } else {
                $begin = mb_substr($filter, 0, $index - 1);
                $end = mb_substr($filter, $index + 1, NULL);
                $filter = $begin . '_' . $end;
            }
            $index = strpos($filter, '_', $index);
        }

        // создание маски для regexp, замена спец символа % на концах
        if (preg_match("/^[%]+[^\s]*[%]$/", $filter) === 1) {
            $filter = mb_substr($filter, 1, -1);
            $filter = "/^[\S]*" . $filter . "+[\S]*/";
        } elseif (preg_match("/^[%]+[^\s]*/", $filter) === 1) {
            $filter = mb_substr($filter, 1, NULL);
            $filter = "/^[\S]*" . $filter . "/";
        } elseif (preg_match("/[^\s]*[%]$/", $filter) === 1) {
            $filter = mb_substr($filter, 0, -1);
            $filter = "/^" . $filter . "+[\S]*/";
        } else {
            $filter = "/^" . $filter . "/";
        }

        return preg_match($filter, $field);
    }
}
