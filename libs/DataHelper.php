<?php

class DataHelper
{
    public static function getMonthsList()
    {
        return array(
            '1' => 'Січень',
            '2' => 'Лютий',
            '3' => 'Березень',
            '4' => 'Квітень',
            '5' => 'Травень',
            '6' => 'Червень',
            '7' => 'Липень',
            '8' => 'Серпень',
            '9' => 'Вересень',
            '10' => 'Жовтень',
            '11' => 'Листопад',
            '12' => 'Грудень',
        );
    }

    public static function prepareFormData($formData, $fields)
    {
        $resultData = array();
        foreach ($fields as $field => $params) {
            if (isset($formData['input-'.$field])) {
                $resultData[$field] = self::prepareFieldByType($formData['input-'.$field], $params['type']);
            } else {
                $resultData[$field] = self::prepareFieldByType(false, $params['type']);
            }
        }
        return $resultData;
    }

    public static function prepareFieldByType($data, $fieldType)
    {
        switch ($fieldType) {
            case 'boolean':
                return $data ? true : false;
                break;
            case 'float':
                return floatval($data);
                break;
            case 'integer':
            case 'primary':
                return intval($data);
                break;
            case 'text':
            default:
                return strval($data);
        }
    }
}