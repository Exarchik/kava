<?php

class AdminListNapoiController extends Controller
{
    public $path = 'list/napoi';

    public $fieldsData = array(
        'id' => array('caption' => 'ID', 'type' => 'primary'),
        'name' => array('caption' => 'Назва', 'type' => 'text'),
        'price' => array('caption' => 'Ціна', 'type' => 'float', 'default' => 0.00),
        'img' => array('caption' => 'Вигляд', 'type' => 'image', 'params' => array('size' => '50px', 'baselink' => 'elements-images/napoi/')),
        'type' => array('caption' => 'Тип', 'type' => 'hidden', 'default' => 'napoi'),
        'ordering' => array('caption' => 'Порядок', 'type' => 'integer', 'default' => 0),
        'is_visible' => array('caption' => 'Відображення', 'type' => 'boolean', 'default' => true),
    );

    public function indexAction($request)
    {
        $pd = new PrepareData($this->db);

        $data = $pd->getNapoiData(false, true);

        $data = $this->typizer->prepareValues($data, $this->fieldsData);

        $params = array(
            'caption' => 'Напої',
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

        $data = $this->db->getRow("SELECT * FROM `_kava_foodrink` WHERE id = {$id}");

        $formData = $this->typizer->prepareDataForForm($data, $this->fieldsData);
        return $this->renderClear('default-form.tpl', array('data' => $formData));
    }
}

