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

    private function replaceLetter($filter, $letter)
    {
        if($letter == '_')
            $replacer = '[\S]';
        elseif($letter == '%')
            $replacer = '[\S]*';
        else
            return $filter;

        $index = strpos($filter, $letter);

        while ($index !== false) {
            if ($index == 0 || $filter[$index - 1] != "\\") {
                $begin = mb_substr($filter, 0, $index);
                $end = mb_substr($filter, $index + 1, NULL);
                $filter = $begin . $replacer . $end;
            } else {
                $begin = mb_substr($filter, 0, $index - 2);
                $end = mb_substr($filter, $index + 1, NULL);
                $filter = $begin . $letter . $end;
            }
            $index = strpos($filter, $letter, $index);
        }

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

        // экранирование спец символов для regexp
        $filter = preg_quote($filter, '/');

        // замена спец символов "%" и "_" в фильтре
        $filter = $this->replaceLetter($filter, '_');
        $filter = $this->replaceLetter($filter, '%');
        $filter = "/^" . $filter . "$/";

        return preg_match($filter, $field) === 1 ? true : false;
    }
}
