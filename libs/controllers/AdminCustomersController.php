<?php

class AdminCustomersController extends Controller
{
    public $path = 'customers';

    public $fieldsData = array(
        'id' => array('caption' => 'ID', 'type' => 'primary'),
        'fio' => array('caption' => 'ПІБ', 'type' => 'text'),
        'deps-code' => array('caption' => 'Код картки', 'type' => 'text'),
        'is_visible' => array('caption' => 'Відображення', 'type' => 'boolean', 'default' => true),
    );

    public function indexAction($request)
    {
        $pd = new PrepareData($this->db);

        $data = $pd->getPersonsData(false, true);

        $data = $this->typizer->prepareValues($data, $this->fieldsData);

        $params = array(
            'caption' => 'Користувачі',
            'path' => $this->path,
            'fields' => $this->fieldsData,
            'data' => $data,
        );

        return $this->renderView('table.tpl', $params);
    }

    public function editAction($request)
    {
        $id = $request['get']['id'] ?? false;
        if ($id === false) {
            return $this->json(['result' => false]);
        }

        $data = $this->db->getRow("SELECT * FROM `_kava_persons` WHERE id = {$id}");

        $formData = $this->typizer->prepareDataForForm($data, $this->fieldsData);
        return $this->renderClear('default-form.tpl', array('data' => $formData));
    }
}

