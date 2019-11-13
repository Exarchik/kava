<?php

class Typizer
{
    public $renderer;

    public $typesList = array();

    public $types = array(
        //'primary' => 'getPrimaryType',
        'image' => 'getImageType',
        'boolean' => 'getBooleanType',
    );

    public $formFieldsType = array(
        'primary' => 'genPrimaryField',
        'hidden' => 'genHiddenField',
        'boolean' => 'genBooleanField',
        'image' => 'genImageField',
    );

    public function __construct($renderer)
    {
        $this->renderer = $renderer;
    }

    public function __($field, $data)
    {
        if (!isset($this->typesList[$field])) {
            return $data;
        }

        if (isset($this->types[$this->typesList[$field]['type']])) {
            return $this->{$this->types[$this->typesList[$field]['type']]}($data, $this->typesList[$field]);
        }
        return $data;
    }

    public function _f($field, $data)
    {
        $methodName = 'genDefaultField';
        if (isset($this->typesList[$field]) && isset($this->formFieldsType[$this->typesList[$field]['type']])) {
            $methodName = $this->formFieldsType[$this->typesList[$field]['type']];
        }
        return $this->{$methodName}($field, $data, $this->typesList[$field]);
    }

    public function setTypeList($typesList)
    {
        $this->typesList = $typesList;
    }

    public function prepareValues($data, $fieldsData)
    {
        $this->setTypeList($fieldsData);
        return $this->processData($data);
    }

    public function prepareDataForForm($data, $fieldsData)
    {
        $this->setTypeList($fieldsData);
        return $this->generateFormFields($data);
    }

    public function processData($data)
    {
        if (empty($this->typesList)) {
            return $data;
        }
        $newData = array();
        foreach ($data as $values) {
            $tmp = array();
            foreach ($values as $field => $value) {
                $tmp[$field] = $this->__($field, $value);
            }
            $newData[] = $tmp;
        }
        return $newData;
    }

    public function generateFormFields($data)
    {
        if (empty($this->typesList)) {
            return $data;
        }
        $formData = array();
        foreach ($data as $field => $value) {
            $formData[$field] = $this->_f($field, $value);
        }
        return $formData;
    }

    /*
        VIEW METHODS
    */
    public function getImageType($value, $attr = [])
    {
        $baselink = $attr['params']['baselink'] ?? '';
        $size = $attr['params']['size'] ?? '';
        return '<img width="'.$size.'" src='.$baselink.$value.' />';
    }

    public function getBooleanType($value, $attr = [])
    {
        return $value == 0
               ? '<i style="color:red" class="fa kava-icon fa-ban" title="ні"></i>'
               : '<i style="color:#2eca07" class="fa kava-icon fa-check-circle" title="так"></i>';
    }

    /*
        FORM FIELDS METHODS
    */
    public function genDefaultField($field, $value, $attr = [])
    {
        return $this->renderer->renderView('fields/field-default.tpl', array('field' => $field, 'value' => $value, 'params' => $attr));
    }

    public function genPrimaryField($field, $value, $attr = [])
    {
        return $this->renderer->renderView('fields/field-primary.tpl', array('field' => $field, 'value' => $value, 'params' => $attr));
    }

    public function genHiddenField($field, $value, $attr = [])
    {
        return $this->renderer->renderView('fields/field-hidden.tpl', array('field' => $field, 'value' => $value, 'params' => $attr));
    }

    public function genBooleanField($field, $value, $attr = [])
    {
        return $this->renderer->renderView('fields/field-boolean.tpl', array('field' => $field, 'value' => $value, 'params' => $attr));
    }

    public function genImageField($field, $value, $attr = [])
    {
        return $this->renderer->renderView('fields/field-image.tpl', array('field' => $field, 'value' => $value, 'params' => $attr));
    }
}