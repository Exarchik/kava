<?php

class AdminKavaDataController extends Controller
{
    public $path = 'kava-data';

    public $fieldsData = array(
        'count' => array('caption' => 'Кількість замовлень', 'type' => 'integer'),
        'money_for_month' => array('caption' => 'Сума замовлень за місяць', 'type' => 'float'),
        'year' => array('caption' => 'Рік', 'type' => 'integer'),
        'month' => array('caption' => 'Місяць', 'type' => 'month'),
    );

    public function indexAction($request)
    {
        $pd = new PrepareData($this->db);

        $data = $pd->getKavaDataByYearsMonths();

        $data = $this->typizer->prepareValues($data, $this->fieldsData);

        $params = array(
            'caption' => 'Замовлення кав`ярні',
            'path' => $this->path,
            'fields' => $this->fieldsData,
            'data' => $data,
            'buttons' => array(
                'view-month-data' => array('icon' => 'fa-list'),
                'edit' => array('hide' => true),
                'delete' => array('hide' => true),
            )
        );

        return $this->renderView('table.tpl', $params);
    }
}