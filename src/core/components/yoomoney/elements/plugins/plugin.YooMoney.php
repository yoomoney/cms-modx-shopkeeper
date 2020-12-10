<?php
/*
 plugin YooMoney
 System event: OnSHKChangeStatus
*/

$e = $modx->Event;

if (!defined('YOOMONEY_PATH')) {
    define('YOOMONEY_PATH', MODX_CORE_PATH."components/yoomoney/");
}
require_once YOOMONEY_PATH.'model/yoomoney.class.php';

if ($e->name == 'OnSHKChangeStatus') {
    $order_id = isset($order_id) ? $order_id : '';
    $status   = isset($status)   ? $status   : '';

    $snippet = $modx->getObject('modSnippet', array('name' => 'YooMoney'));
    $config  = $snippet->getProperties();
    $order = $modx->getObject('SHKorder', array('id'=>$order_id));
    $yooMoney = new Yoomoney($modx, $config);
    $yooMoney->hookSendSecondReceipt($order, $status);
}
