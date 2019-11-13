<?php

define("FENOM_LIBS", __DIR__."/../../vendor/fenom/src/");

require FENOM_LIBS."Fenom/ProviderInterface.php";
require FENOM_LIBS."Fenom/Provider.php";
require FENOM_LIBS."Fenom/Accessor.php";
require FENOM_LIBS."Fenom/Compiler.php";
require FENOM_LIBS."Fenom/Tag.php";
require FENOM_LIBS."Fenom/RangeIterator.php";
require FENOM_LIBS."Fenom/Error/CompileException.php";
require FENOM_LIBS."Fenom/Error/InvalidUsageException.php";
require FENOM_LIBS."Fenom/Error/SecurityException.php";
require FENOM_LIBS."Fenom/Error/TokenizeException.php";
require FENOM_LIBS."Fenom/Error/UnexpectedTokenException.php";
require FENOM_LIBS."Fenom/Modifier.php";
require FENOM_LIBS."Fenom/Tokenizer.php";
require FENOM_LIBS."Fenom/Render.php";
require FENOM_LIBS."Fenom/Template.php";
require FENOM_LIBS."Fenom.php";

class FenomRenderer
{
    public $engine;

    public function __construct($template_dir, $options = false)
    {
        $this->engine = $fenom = Fenom::factory($template_dir, $template_dir.'cache/', $options);
    }

    // передает параметры шаблону и строит по нему результат выводит в stdout
    public function render($template, $params)
    {
        return $this->engine->display($template, $params);
    }

    // передает параметры шаблону и строит по нему результат возвращает string
    public function renderView($template, $params)
    {
        return $this->engine->fetch($template, $params);
    }

    // передает на выход json из данных
    public function json($data)
    {
        return json_encode($data);
    }
}

/*
$test = new FenomRenderer(__DIR__."/../../templates/", []);

$result = $test->engine->display('test.tpl', ['datas' => array('test1', 'test2')]);
*/