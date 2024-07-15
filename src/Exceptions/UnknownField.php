<?php
namespace CP\Filter\Exceptions;

class UnknownField extends \Exception{
    protected $field_name;

    public function __construct(array $field_name)
    {
        parent::__construct("Unknown field: " . implode(', ', $field_name));
        $this->field_name = $field_name;
    }

    public function getFieldName(): array
    {
        return $this->field_name;
    }

}