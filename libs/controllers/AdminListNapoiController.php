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

    public function deleteAction($request)
    {
        $id = $request['get']['id'] ?: false;
        $confirm = $request['post']['confirm'] ?: 'no';
        if ($id === false) {
            return $this->alert('Не вказано ID');
        }
        if ($confirm == 'yes') {
            $result = $this->db->query("DELETE FROM `_kava_foodrink` WHERE id = {$id}");
            if ($result == false) {
                return $this->alert('Виникла помилка');    
            }
            return $this->alert('Видалено успішно', true);
        }

        $params = array(
            'link' => 'l='.$this->path.'&a=delete&id='.$id,
            'caption' => 'Видалення',
            'message' => 'Видалити запис №'.$id,
        );
        return $this->renderClear('answer-form.tpl', $params);
    }

    public function editAction($request)
    {
        $id = $request['get']['id'] ?: false;
        if ($id === false) {
            $data = array_fill_keys(array_keys($this->fieldsData), '');
        } else {
            $data = $this->db->getRow("SELECT * FROM `_kava_foodrink` WHERE id = {$id}");
        }

        $formData = $this->typizer->prepareDataForForm($data, $this->fieldsData);
        return $this->renderClear('default-form.tpl', array('path' => $this->path, 'data' => $formData));
    }
    public function sendFormAction($request)
    {
        $preparedData = DataHelper::prepareFormData($request['post'], $this->fieldsData);

        $dataSql = array();
        if (!empty($preparedData['id'])) {
            foreach ($preparedData['data'] as $key => $value) {
                $dataSql[] = "`{$key}` = {$this->db->quote($value)}";
            }
            $sql = "UPDATE `_kava_foodrink` SET ".join(', ', $dataSql)." WHERE `id` = {$preparedData['id']}";
        } else {
            $sql = "INSERT INTO `_kava_foodrink` (".join(',', $this->db->quoteAllFields(array_keys($preparedData['data']))).") VALUES (".join(',', $this->db->quoteAll($preparedData['data'])).")";
        }

        $result = $this->db->query($sql);
        return $this->json(array('result' => ($result != false)));
    }
}

