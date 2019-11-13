<?php

function getListOfAvailableIps() {
    return array(
        '77.52.105.188',
        '192.168.251.34',
        '127.0.0.1',
    );
}

function checkAvailableIps($remote_addr) {
    $listOfAvailableIps = getListOfAvailableIps();

    if (in_array($remote_addr, $listOfAvailableIps)
        || (strpos('+'.$remote_addr,'10.11.10')) 
        || (strpos('+'.$remote_addr,'10.13.10'))
        || (strpos('+'.$remote_addr,'10.13.0.22'))) {
        return true;
    }
    return false;
}

function admin_link($shortPath = '') {
    if (!$shortPath) {
        return ADMIN_LINK;
    }

    return ADMIN_LINK."?l=".$shortPath;
}

// выпили потом этот метод больше не нужон
function methodByAction($action, $subLink = '') {
    if (empty($action)) {
        return false;
    }
    if (!empty($subLink)) {
        $action .= '-for-'.$subLink;
    }
    $action = str_replace(array('_','-'), ' ', $action);
    return 'action'.join('', array_map('ucfirst', explode(' ', $action)));
}

function methodByRequestData($request) {

}

function parseUrl($request = array(), $appType = 'web') {
    $urlData = array(
        'app' => $appType,
        'base' => $appType == 'web' ? BASE_LINK : ADMIN_LINK,
        'link' => 'main',
        'sublink' => '',
        'action' => 'index',
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
        $urlData['action'] = $request['a'];
    }

    // id
    if (isset($request['id'])) {
        $urlData['id'] = intval($request['id']);
    }

    $urlData['controllerName'] = ucfirst($urlData['app'])
        .join('',array_map('ucfirst',explode(' ',str_replace(array('-','_'),' ',$urlData['link']))))
        .ucfirst($urlData['sublink'])
        .'Controller';
    $urlData['actionMethod'] = $urlData['action'].'Action';

    //$methodName = methodByAction($urlData['link'], $urlData['sublink']);

    return $urlData;
}
