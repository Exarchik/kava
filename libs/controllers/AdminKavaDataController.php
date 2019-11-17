<?php

class AdminKavaDataController extends Controller
{
    public $path = 'kava-data';

    public $fieldsData = array(
        'id' => array('caption' => 'ID', 'type' => 'primary'),
        'surname' => array('caption' => 'Прізвище', 'type' => 'text'),
        'order_time' => array('caption' => 'Час замовлення', 'type' => 'text'),
        'summary' => array('caption' => 'Сума, грн', 'type' => 'float'),
    );

    public function indexAction($request)
    {
        $fieldsData = array(
            'count' => array('caption' => 'Кількість замовлень', 'type' => 'integer'),
            'money_for_month' => array('caption' => 'Сума замовлень за місяць', 'type' => 'float'),
            'year' => array('caption' => 'Рік', 'type' => 'integer'),
            'month' => array('caption' => 'Місяць', 'type' => 'month'),
        );

        $pd = new PrepareData($this->db);

        $data = $pd->getKavaDataByYearsMonths();

        $data = $this->typizer->prepareValues($data, $fieldsData);

        $params = array(
            'caption' => 'Замовлення кав`ярні',
            'path' => $this->path,
            'fields' => $fieldsData,
            'data' => $data,
            'buttons' => array(
                'view-month-data' => array('icon' => 'fa-list', 'not-ajax' => true),
                'edit' => false,
                'delete' => false,
            ),
            'indexField' => 'index'
        );

        return $this->renderView('table.tpl', $params);
    }

    public function viewMonthDataAction($request)
    {
        $index = $request['get']['id'] ?? false;
        if (!$index) {
            return "index it empty!";
        }
        list($iYear, $iMonth) = explode('-', $index);
        if (empty($iYear) || empty($iMonth)) {
            return "not correct index!";
        }

        $sql = "SELECT * FROM `_kava_data` WHERE YEAR(order_time) = '{$iYear}' AND MONTH(order_time) = '{$iMonth}' ORDER BY order_time ASC";
        $data = $this->db->getAll($sql);
        $data = $this->typizer->prepareValues($data, $this->fieldsData);

        $params = array(
            'caption' => 'Замовлення кав`ярні '.DataHelper::getMonthsList()[$iMonth].' '.$iYear,
            'path' => $this->path,
            'fields' => $this->fieldsData,
            'data' => $data,
            'buttons' => array(
                'edit' => false,
                'delete' => false,
            ),
        );

        return $this->renderView('table.tpl', $params);
    }
}