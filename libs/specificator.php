<?php

class Specificator
{
    public $specifications = array();

    public $types = array(
        'image' => 'getImageSpecification',
        'boolean' => 'getBooleanSpecification',
    );

    public function __($field, $data)
    {
        if (!isset($this->specifications[$field])) {
            return $data;
        }

        if (isset($this->types[$this->specifications[$field]['type']])) {
            return $this->{$this->types[$this->specifications[$field]['type']]}($data, $this->specifications[$field]);
        }
        return $data;
    }

    public function setSpecification($specifications)
    {
        $this->specifications = $specifications;
    }

    public function getImageSpecification($value, $params = [])
    {
        $baselink = $params['baselink'] ?? '';
        $size = $params['size'] ?? '';
        return '<img width="'.$size.'" src='.$baselink.$value.' />';
    }

    public function getBooleanSpecification($value, $params = [])
    {
        return $value == 0
               ? '<i style="color:red" class="fa kava-icon fa-ban" title="ні"></i>'
               : '<i style="color:#2eca07" class="fa kava-icon fa-check-circle" title="так"></i>';
    }
}