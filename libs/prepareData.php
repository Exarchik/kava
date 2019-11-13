<?php

class PrepareData
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function prepare($data, $isJson)
    {
        return $isJson ? json_encode($data) : $data;
    }

    public function getPersonsData($isJson = false, $forAdmin = false)
    {
        $sqlFields = "*";
        $sqlWhere = "1";
        $method = 'getAll';
        if (!$forAdmin) {
            $sqlFields = "fio";
            $sqlWhere = "`is_visible` = 1";
            $method = 'getCol';
        }
        return $this->prepare($this->db->{$method}("SELECT {$sqlFields} FROM `_kava_persons` WHERE {$sqlWhere}"), $isJson);
    }

    public function getNapoiData($isJson = false, $forAdmin = false)
    {
        $sqlWhere = "`type`='napoi'";
        if (!$forAdmin) {
            $sqlWhere .= "AND `is_visible` = 1";
        }
        return $this->prepare($this->db->getAll("SELECT * FROM `_kava_foodrink` WHERE {$sqlWhere} ORDER BY ordering ASC"), $isJson);
    }

    public function getSnackData($isJson = false, $forAdmin = false)
    {
        $sqlWhere = "`type`='snack'";
        if (!$forAdmin) {
            $sqlWhere .= "AND `is_visible` = 1";
        }
        return $this->prepare($this->db->getAll("SELECT * FROM `_kava_foodrink` WHERE {$sqlWhere} ORDER BY ordering ASC"), $isJson);
    }

    public function getBooksData($isJson = false, $forAdmin = false)
    {
        /*$sql = "SELECT b.*, b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0) AS `amount`
                FROM books AS b
                WHERE b.is_visible = 1
                ORDER BY b.caption ASC";*/
        $sqlWhere = "1";
        if (!$forAdmin) {
            $sqlWhere = "b.is_visible = 1";
        }
        $hasBooks = array();
        $hasNoBooks = array();    
        $sql = "SELECT b.*, (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0) AS get_amount
                FROM books AS b
                WHERE {$sqlWhere}
                ORDER BY b.caption ASC";
        $booksList = $this->db->getAll($sql);

        if ($forAdmin) {
            return $isJson ? json_decode($booksList) : $booksList;
        }

        foreach ($booksList as $book) {
            $currentAmount = $book['amount'] - $book['get_amount'];
            if ($book['get_amount'] == 0) {
                $book['is_get'] = 0;
                $hasBooks[] = $book;
            } elseif ($currentAmount != 0 && $currentAmount < $book['amount']) {
                $book['amount'] = $currentAmount;
                $book['is_get'] = 0;
                $hasBooks[] = $book;
                $book['amount'] = $book['get_amount'];
                $book['is_get'] = 1;
                $hasNoBooks[] = $book;
            } else {
                $book['is_get'] = 1;
                $hasNoBooks[] = $book;
            }
        }

        return $isJson ? json_encode(array_merge($hasBooks, $hasNoBooks)) : array_merge($hasBooks, $hasNoBooks);
    }
}

/*
SELECT tmp.* FROM (
SELECT b.*, b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0) AS `amount`
FROM books AS b
WHERE b.is_visible = 1
ORDER BY b.ordering ASC) AS tmp

SELECT tmp.* FROM (
SELECT b.caption AS _caption, b.author AS _author, b.img AS _img, b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0) AS `amount`, b.ordering AS ordering
FROM books AS b
WHERE b.is_visible = 1
ORDER BY b.ordering ASC) AS tmp
ORDER BY IF(tmp.amount>0,1,0) ASC, tmp._caption


SELECT b.*, (b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0)) AS amount
                FROM books AS b
                WHERE b.is_visible = 1 AND (b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0)) > 0
ORDER BY b.caption
UNION
SELECT b.*, (b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0)) AS amount
                FROM books AS b
                WHERE b.is_visible = 1 AND (b.amount - (SELECT count(*) FROM `data_books` AS db WHERE db.book_id = b.id AND db.is_returned = 0)) < 1
ORDER BY b.caption
*/