<?php

class AdminListBooksController extends Controller
{
    public $path = 'list/books';

    public $fieldsData = array(
        'id' => array('caption' => 'ID', 'type' => 'primary'),
        'caption' => array('caption' => 'Назва', 'type' => 'text'),
        'author' => array('caption' => 'Автор', 'type' => 'text'),
        'img' => array('caption' => 'Вигляд', 'type' => 'image', 'params' => array('size' => '50px', 'baselink' => 'elements-images/books/')),
        'amount' => array('caption' => 'Кількість', 'type' => 'integer'),
        'get_amount' => array('caption' => 'У користуванні', 'type' => 'primary'),
        'ordering' => array('caption' => 'Порядок', 'type' => 'integer', 'default' => 0),
        'is_visible' => array('caption' => 'Відображення', 'type' => 'boolean', 'default' => true),
    );

    public function indexAction($request)
    {
        $pd = new PrepareData($this->db);

        $data = $pd->getBooksData(false, true);

        $data = $this->typizer->prepareValues($data, $this->fieldsData);

        $params = array(
            'caption' => 'Бібліотека',
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

        $data = $this->db->getRow("SELECT * FROM `books` WHERE id = {$id}");

        $formData = $this->typizer->prepareDataForForm($data, $this->fieldsData);
        return $this->renderClear('default-form.tpl', array('path' => $this->path, 'data' => $formData));
    }
}

