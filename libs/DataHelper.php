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
        $resultData = array('id' => 0, 'data' => array());
        foreach ($fields as $field => $params) {
            $tmpData = isset($formData['input-'.$field]) ? $formData['input-'.$field] : false;
            $tmpData = self::prepareFieldByType($tmpData, $params['type']);

            if ($field == 'id') {
                $resultData['id'] = $tmpData;
            } else {
                $resultData['data'][$field] = $tmpData;
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