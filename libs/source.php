<?php

class Source
{
    public $db;
    public $prepareData;

    public function __construct($db, $prepareData)
    {
        $this->db = $db;
        $this->prepareData = $prepareData;
    }

    // заказывает книжку
    public function actionBooksSend($_data)
    {
        header('Content-Type: application/json');
        // фамилия клиента
        $surname = $_data['client'];
        // IP-адресс с которого сделан заказ
        $remote_addr = $_data['remote_addr'];

        $getDate = date('Y-m-d H:i:s');
        $returnDate = $_data['return_date'];
        
        $sqlData = array();
        foreach ($_data['order'] as $order) {
            $sqlData[] = "('".$remote_addr."', '".$surname."', '".$order['prod_id']."', '".($order['prod_author']." - ".$order['prod_name'])."', '".$getDate."', '".$returnDate."')";
        }
        $sql = "INSERT INTO `data_books` (`client-ip`, `surname`, `book_id`, `books`, `get-date`, `return-date`)
                VALUES ".join(', ', $sqlData);
        $result = $this->db->query($sql);

        return json_encode(array('result' => ($result != false), 'new_books_list' => $this->prepareData->getBooksData()));
    }

    // выводим список книжек у конкретного клиента
    public function actionGetBookClientOrder($_data)
    {
        header('Content-Type: application/json');
        $name = $_data['name'];
        $todayDate = date('Y-m-d H:i:s');
        $plusThreeDaysDate = date('Y-m-d H:i:s', strtotime('+3 days'));

        $sql = "SELECT db.*, b.img FROM `data_books` db
                JOIN `books` b ON b.id = db.book_id
                WHERE db.`surname` = '".$name."' AND db.`is_returned` = 0
                ORDER BY db.`return-date` ASC, db.`id` ASC";
        $booksData = $this->db->getAll($sql);

        $showBooksData = array();

        foreach ($booksData as $data) {
            $data['warning'] = 'green';
            if ($data['return-date'] > $todayDate && $data['return-date'] <= $plusThreeDaysDate) {
                $data['warning'] = 'yellow';
            } elseif ($data['return-date'] <= $todayDate) {
                $data['warning'] = 'red';
            }

            $data['return-date'] = date('Y-m-d', strtotime($data['return-date']));
            $data['get-date'] = date('Y-m-d', strtotime($data['get-date']));
            $showBooksData[] = $data;
        }

        if (!empty($showBooksData)) {
            return json_encode(array('result' => $showBooksData));
        }
        return json_encode(array('result' => false));
    }

    // отправляем заказ кафетерия
    public function actionOrderSend($_data)
    {
        header('Content-Type: application/json');
        // фамилия клиента
        $surname = $_data['client'];
        // заказ клиента
        $order = $_data['order'];
        // общая сумма заказа
        $summary = $_data['price'];
        // IP-адресс с которого сделан заказ
        $remote_addr = $_data['remote_addr'];
        // сериализированный заказ 
        $_order = serialize($order);
        
        // запрос заказа 
        $sql = "INSERT INTO `_kava_data` (`client-ip`, `surname`, `products`, `summary`)
                VALUES ('".$remote_addr."', '".$surname."', '".$_order."', ".$summary." )";
        $result = $this->db->query($sql);

        return json_encode(array('result' => ($result != false)));
    }

    // возрат книжки по ИД транзакции
    public function actionReturnBook($_data)
    {
        header('Content-Type: application/json');
        $returnBookId = $_data['id'];

        $result = $this->db->query("UPDATE `data_books` SET `is_returned` = 1 WHERE `id` = {$returnBookId}");
        return json_encode(array('result' => ($result != false), 'new_books_list' => $this->prepareData->getBooksData()));
    }
}