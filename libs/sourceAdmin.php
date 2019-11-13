<?php

require_once("pageBuilder.php");

class SourceAdmin
{
    public $db;
    public $config;
    public $request;
    public $pageBuilder;
    public $prepareData;
    public $availableActions;

    public $baseAdminLink;

    public $uriData = array('action' => '');

    public function __construct($db, $prepareData, $config, $request)
    {
        $this->db = $db;
        $this->config = $config;
        $this->request = $request;
        $this->prepareData = $prepareData;

        $this->availableActions = array('view', 'edit', 'save', 'delete');

        $this->baseAdminLink = $config->base_link."/admin.php";

        $this->pageBuilder = new PageBuilder($db, $prepareData, $this->parseUrl());
    }

    public function parseUrl($request = false)
    {
        $request = $request !== false ? $reuest : $this->request;
        $urlData = array(
            'base' => $this->baseAdminLink,
            'link' => 'main',
            'sublink' => '',
            'action' => 'view',
            'id' => 0,
        );
        
        // link and sublink
        if (isset($request['l'])) {
            $linkData = explode('/', $request['l']);
            $urlData['link'] = $linkData[0];
            if (count($linkData) > 1) {
                $urlData['sublink'] = $linkData[1];
            }
        }

        // action
        if (isset($request['a'])) {
            if (in_array($request['a'], $this->availableActions)) {
                $urlData['action'] = $request['a'];
            }
        }

        // id
        if (isset($request['id'])) {
            $urlData['id'] = intval($request['id']);
        }

        $methodName = methodByAction($urlData['link'], $urlData['sublink']);

        return array_merge($urlData, array('method' => $methodName));
    }

    public function genLink($shortPath = '')
    {
        if (!$shortPath) {
            return $this->baseAdminLink;
        }

        return $this->baseAdminLink."?l=".$shortPath;
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
        $requestData = $this->parseUrl();
        if (method_exists($this->pageBuilder, $requestData['method'])) {
            $this->pageBuilder->{$requestData['method']}($requestData);
        }
    }
}