<?php

class App
{
    const APP_TYPE_WEB = 'web';
    const APP_TYPE_ADMIN = 'admin';

    public $db;
    // web or admin
    public $appType = 'web';
    // GET and POST data
    public $request = [];

    public function __construct($db, $appType = 'web')
    {
        $this->db = $db;
        $this->appType = $appType;
    }

    public function getMenuData()
    {
        $brand = array('link' => '', 'caption' => "Головна");
        $current_link = isset($this->request['l']) ? $this->request['l'] : '';
        $menus = array(
            'list' => array("caption" => "Списки",  "items" => array(
                'list/napoi' => array("caption" => "Напої", "items" => null),
                'list/snack' => array("caption" => "Снеки", "items" => null),
                'list/books' => array("caption" => "Бібліотека", "items" => null),
            )),
            'customers' => array("caption" => "Користувачі",  "items" => null),
            'kava-data' => array("caption" => "Замовлення кав'ярні",  "items" => null),
            'books-data' => array("caption" => "Замовлення бібліотеки",  "items" => null),
        );

        return array('brand' => $brand, 'current_link' => $current_link, 'menus' => $menus);
    }

    public function process()
    {
        $this->request = $this->generateRequest();

        $controllerData = parseUrl($this->request['get'], $this->appType);

        $theController = Controller::factory($controllerData['controllerName'], $this->db);

        $appController = new Controller($db);

        if (!method_exists($theController, $controllerData['actionMethod'])) {
            $content = $theController->display('Розділ недоступний або не існує!');
        } else {
            $content = $theController->{$controllerData['actionMethod']}($this->request);
        }
        
        if ($theController->returnType == 'json') {
            return $this->json($content);
        }

        if ($theController->returnType == 'display' || $theController->returnType == 'renderClear') {
            return $this->display($content);
        }

        return $appController->render('admin-layout.tpl', array(
            'menu' => $appController->renderView('menu.tpl', $this->getMenuData()),
            'content' => $content,
            'info' => print_r($controllerData, 1),
        ));
    }

    public function json($content)
    {
        header('Content-Type: application/json');
        print $content;
        return;
    }

    public function display($content)
    {
        header('Content-Type: text/html');
        print $content;
        return;
    }

    // appType + controller + "Controller" = ControllerName
    private function generateControllerData($requestData)
    {
        $controllerData = array(
            'app' => $this->appType,
            'controller' => 'main',
            'action' => 'index'
        );

        $parsedUrl = parseUrl($requestData['get'], $this->appType);

        return ['controllerData' => $controllerData, 'parsedUrl' => $parsedUrl];
    }

    private function generateRequest()
    {
        return [
            'get' => $_GET,
            'post' => $_POST,
            'request' => $_REQUEST,
            'server' => $_SERVER,
        ];
    }
}