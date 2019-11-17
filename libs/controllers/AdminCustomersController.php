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
            'buttons' => array(
                'add' => array('caption' => 'Додати користувача'),
            ),
        );

        return $this->renderView('table.tpl', $params);
    }

    public function deleteAction($request)
    {
        $id = $request['get']['id'] ?: false;
        $confirm = $request['post']['confirm'] ?: 'no';
        if ($id === false) {
            return $this->display('Не вказано ID');
        }
        if ($confirm == 'yes') {
            $result = $this->db->query("DELETE FROM `_kava_persons` WHERE id = {$id}");
            if ($result == false) {
                return $this->display('Виникла помилка');    
            }
            return $this->renderClear('message-form.tpl', array(
                'message' => 'Видалено успішно',
                'reloadPage' => true
            ));
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
            $data = $this->db->getRow("SELECT * FROM `_kava_persons` WHERE id = {$id}");
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
            $sql = "UPDATE `_kava_persons` SET ".join(', ', $dataSql)." WHERE `id` = {$preparedData['id']}";
        } else {
            $sql = "INSERT INTO `_kava_persons` (".join(',', $this->db->quoteAllFields(array_keys($preparedData['data']))).") VALUES (".join(',', $this->db->quoteAll($preparedData['data'])).")";
        }

        $result = $this->db->query($sql);
        return $this->json(array('result' => ($result != false)));
    }
}

