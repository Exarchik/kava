<?php

require_once "FenomRenderer.php";
require_once _LIBS."typizer.php";

class Controller
{
    public $db;
    public $renderer;
    public $typizer;

    public $returnType = 'render';

    public function __construct($db, $tmplDir = _TEMPLATES, $options = array())
    {
        $this->db = $db;

        $this->renderer = new FenomRenderer($tmplDir, $options);
        $this->typizer = new Typizer($this->renderer);
    }

    // передает параметры шаблону и строит по нему результат выводит в stdout
    public function render($template, $params)
    {
        $this->returnType = 'render';
        return $this->renderer->render($template, $params);
    }

    // передает параметры шаблону и строит по нему результат возвращает string
    public function renderView($template, $params)
    {
        $this->returnType = 'renderView';
        return $this->renderer->renderView($template, $params);
    }

    // передает параметры шаблону и строит по нему результат возвращает string игнорируя оcновной layout 
    public function renderClear($template, $params)
    {
        $this->returnType = 'renderClear';
        return $this->renderer->renderView($template, $params);
    }

    // выводит текст игнорируя оcновной layout
    public function display($content)
    {
        $this->returnType = 'display';
        return $content;
    }

    // передает на выход json из данных
    public function json($data)
    {
        $this->returnType = 'json';
        return $this->renderer->json($data);
    }

    public static function factory($controllerClass, $db, $tmplDir = _TEMPLATES)
    {
        if (file_exists(_LIBS_CONTROLLERS . $controllerClass . ".php")) {
            require_once _LIBS_CONTROLLERS . $controllerClass . ".php";
            return new $controllerClass($db, $tmplDir);
        } 
        return new self($db, $tmplDir);
    }
}

/*
$controller = new Controller([]);

$result = $controller->renderView('test.tpl', ['datas' => array('test1', 'test2')]);
*/