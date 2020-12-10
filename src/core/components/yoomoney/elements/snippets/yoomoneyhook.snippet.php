<?php

$eventName = $modx->event->name;
$_isAdmin = ($modx->user->sudo == 1);

if (!defined('YOOMONEY_PATH')) {
    define('YOOMONEY_PATH', MODX_CORE_PATH."components/yoomoney/");
}
require_once YOOMONEY_PATH.'model/yoomoney.class.php';

$snippet = $modx->getObject('modSnippet',array('name' => 'YooMoney'));
$config = $snippet->getProperties();

$ym = new Yoomoney($modx, $config);

if (!empty($_SESSION['shk_lastOrder']) && !empty($_SESSION['shk_lastOrder']['id'])) {
    $ym->pay_method = !empty($_SESSION['shk_lastOrder']['payment']) ? $_SESSION['shk_lastOrder']['payment'] : '';
    $order_id = (int)$_SESSION['shk_lastOrder']['id'];
}
if (!empty($_POST['payment'])) $ym->pay_method = $_POST['payment'];
if (!empty($_POST['email'])) $ym->email = $_POST['email'];
if (!empty($_POST['phone'])) $ym->phone = $_POST['phone'];
if (!empty($_POST['alfaLogin'])) $ym->alfaLogin = $_POST['alfaLogin'];
if (!empty($_POST['qiwiPhone'])) $ym->qiwiPhone = $_POST['qiwiPhone'];

if (!$ym->checkPayMethod()) {
    return false;
}

$modx->addPackage('shopkeeper', MODX_CORE_PATH."components/shopkeeper/model/");
$order = $modx->getObject('SHKorder', array('id' => $order_id));
if (!$order) {
    return false;
}
$output = '';

if ($order_id && $_POST['order']) {
    $ym->userId = $modx->getLoginUserID('web') ? $modx->getLoginUserID('web') : 0;
    $ym->orderId = $order_id;
    $ym->orderTotal = $_SESSION['shk_lastOrder']['price'];
    $ym->orderTotal = floatval(str_replace(array(',',' '), array('.',''), $ym->orderTotal));
    $ym->comment = $_POST['message'];

    $_host = str_replace(array('http://', 'https://'), '' , $modx->config['site_url']);
    $host = 'https://' . $_host . 'assets/components/yoomoney/connector_result.php';
    $ym->successUrl = $host.'?success=1';
    $ym->failUrl = $host.'?fail=1';

    echo $ym->createFormHtml();
    exit;
}
return $output;
