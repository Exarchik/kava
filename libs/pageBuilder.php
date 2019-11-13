<?php

require_once('specificator.php');

class pageBuilder
{
    public $db;
    public $urlData;
    public $prepareData;

    public function __construct($db, $prepareData, $urlData)
    {
        $this->db = $db;
        $this->urlData = $urlData;
        $this->prepareData = $prepareData;

        $this->specificator = new Specificator();
    }

    public function __($field, $data)
    {
        return $this->specificator->__($field, $data);
    }

    public function setSpecification($specifications)
    {
        $this->specificator->setSpecification($specifications);
    }

    public function actionListForNapoi($params = array())
    {
        $path = 'list/napoi';
        $caption = 'Напої';
        $fields = array(
            'name' => 'Назва',
            'price' => 'Ціна',
            'img' => 'Вигляд',
            'ordering' => 'Порядок',
            'is_visible' => 'Відображення',
        );
        $this->setSpecification(
            array(
                'img' => array('type' => 'image', 'size' => '50px', 'baselink' => 'elements-images/napoi/'),
                'is_visible' => array('type' => 'boolean'),
            )
        );

        if ($params['action'] == 'edit') {
            return $this->generateEditForm('Редагувати напій', $params);
        }

        $data = $this->prepareData->getNapoiData(false, true);
        return $this->generateView($caption, $path, $fields, $data);
    }

    public function actionListForSnack($params = array())
    {
        $path = 'list/snack';
        $caption = 'Снеки';
        $fields = array(
            'name' => 'Назва',
            'price' => 'Ціна',
            'img' => 'Вигляд',
            'ordering' => 'Порядок',
            'is_visible' => 'Відображення',
        );
        $this->setSpecification(
            array(
                'img' => array('type' => 'image', 'size' => '50px', 'baselink' => 'elements-images/snack/'),
                'is_visible' => array('type' => 'boolean'),
            )
        );
        $data = $this->prepareData->getSnackData(false, true);
        
        $this->generateView($caption, $path, $fields, $data);
    }

    public function actionListForBooks($params = array())
    {
        $path = 'list/books';
        $caption = 'Бібліотека';
        $fields = array(
            'caption' => 'Назва',
            'author' => 'Автор',
            'img' => 'Вигляд',
            'amount' => 'Кількість',
            'get_amount' => 'У користуванні',
            'ordering' => 'Порядок',
            'is_visible' => 'Відображення',
        );
        $this->setSpecification(
            array(
                'img' => array('type' => 'image', 'size' => '50px', 'baselink' => 'elements-images/books/'),
                'is_visible' => array('type' => 'boolean'),
            )
        );
        $data = $this->prepareData->getBooksData(false, true);
        
        $this->generateView($caption, $path, $fields, $data);
    }

    public function actionCustomers($params = array())
    {
        $path = 'customers';
        $caption = 'Користувачі';
        $fields = array(
            'fio' => 'ПІБ',
            'is_visible' => 'Відображення',
        );
        $this->setSpecification(
            array(
                'is_visible' => array('type' => 'boolean'),
            )
        );
        $data = $this->prepareData->getPersonsData(false, true);
        
        $this->generateView($caption, $path, $fields, $data);
    }

    public function actionLogout($params = array())
    {
        // not a real method
    }

    protected function generateEditForm($caption, $params)
    {
        /*$data = $caption . " - ". print_r($params, 1);
        return $data;*/
        return json_encode([$caption, $prams]);
    }

    protected function generateView($caption, $path, $fields, $data)
    {   
        $cnt = 0;
        ?>
        <h2><?=$caption?></h2>
        <table id="generated-table" class="table table-bordered">
            <thead>
                <tr>
                <th> # </th>
                <?php
                    foreach($fields as $key => $value) {
                        ?>
                        <th> <?=$value?> </th>
                        <?php
                    }
                ?>
                <th> Керування </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($data as $key => $values) {
                ?>
                <tr>
                    <td> <?=++$cnt?> </td>
                <?php
                    foreach($fields as $key => $value) {
                ?>
                        <td> <?=$this->__($key, $values[$key])?> </td>
                <?php
                    }
                ?>
                    <td>
                        <a class="edit-row" data-path="<?=$path?>" data-id="<?=$values['id']?>" href="#"><i style="color:blue;" class="fa kava-icon fa-edit"></i></a>&nbsp;
                        <a class="delete-row" data-path="<?=$path?>" data-id="<?=$values['id']?>" href="#"><i style="color:red;" class="fa kava-icon fa-remove"></i></a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}