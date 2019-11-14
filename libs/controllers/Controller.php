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
        $params = $this->prepareParameters($params);
        $this->returnType = 'render';
        return $this->renderer->render($template, $params);
    }

    // передает параметры шаблону и строит по нему результат возвращает string
    public function renderView($template, $params)
    {
        $params = $this->prepareParameters($params);
        $this->returnType = 'renderView';
        return $this->renderer->renderView($template, $params);
    }

    // передает параметры шаблону и строит по нему результат возвращает string игнорируя оcновной layout 
    public function renderClear($template, $params)
    {
        $params = $this->prepareParameters($params);
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

    // базовые параметры для Рендерера
    public function getDefaultParameters()
    {
        return array(
            // кнопки по умолчанию чтобы спрятать нужно в параметрах передать hide => true
            'buttons' => array(
                'edit' => array('icon' => 'fa-edit', 'color' => 'blue'),
                'delete' => array('icon' => 'fa-remove', 'color' => 'red'),
            ),
            // поле по котрому отправляются запросы на сервер
            'indexField' => 'id',
        );
    }

    //
    public function prepareParameters($params)
    {
        $newParameters = $this->getDefaultParameters();

        foreach($params as $key => $param) {
            if (!isset($newParameters[$key])) {
                $newParameters[$key] = $param;
            } else {
                if (is_array($param)) {
                    $newParameters[$key] = array_merge_recursive($newParameters[$key], $param);
                } else {
                    $newParameters[$key] = $param;
                }
            }
        }

        return $newParameters;
    }

    public static function factory($controllerClass, $db, $tmplDir = _TEMPLATES)
    {
        if (file_exists(_LIBS_CONTROLLERS . $controllerClass . ".php")) {
            require_once _LIBS_CONTROLLERS . $controllerClass . ".php";
            return new $controllerClass($db, $tmplDir);
        } 
        return new self($db, $tmplDir);
    }

    // базовый метод просто выводит пустую строчку
    public function indexAction($request)
    {
        return '<pre>--цього розділу насправді не існує--</pre>';
    }
}