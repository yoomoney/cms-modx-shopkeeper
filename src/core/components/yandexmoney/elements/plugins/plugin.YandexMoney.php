<?php
/*
 plugin YandexMoney
 System event: OnSHKChangeStatus
*/

$e = $modx->Event;

if (!defined('YANDEXMONEY_PATH')) {
    define('YANDEXMONEY_PATH', MODX_CORE_PATH."components/yandexmoney/");
}
require_once YANDEXMONEY_PATH.'model/yandexmoney.class.php';

if ($e->name == 'OnSHKChangeStatus') {
    $order_id = isset($order_id) ? $order_id : '';
    $status   = isset($status)   ? $status   : '';

    $snippet = $modx->getObject('modSnippet', array('name' => 'YandexMoney'));
    $config  = $snippet->getProperties();
    $order = $modx->getObject('SHKorder', array('id'=>$order_id));
    $yandexMoney = new Yandexmoney($modx, $config);
    $yandexMoney->hookSendSecondReceipt($order, $status);
}
