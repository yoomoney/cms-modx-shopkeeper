<?php

$eventName = $modx->event->name;
$_isAdmin = ($modx->user->sudo == 1);

if (!defined('YANDEXMONEY_PATH')) {
    define('YANDEXMONEY_PATH', MODX_CORE_PATH."components/yandexmoney/");
}
require_once YANDEXMONEY_PATH.'model/yandexmoney.class.php';


$ym = new Yandexmoney($modx, $scriptProperties);

if(!empty($_SESSION['shk_lastOrder']) && !empty($_SESSION['shk_lastOrder']['id'])) {
    $ym->pay_method = !empty($_SESSION['shk_lastOrder']['payment']) ? $_SESSION['shk_lastOrder']['payment'] : '';
    $order_id = (int)$_SESSION['shk_lastOrder']['id'];
}

if (!empty($_POST['payment'])) {
    $ym->pay_method = $_POST['payment'];
}

if ($scriptProperties['action']=='showMethods') {
    $org_mode = ($scriptProperties['mode'] == 2 || $scriptProperties['mode'] == 3);
    $output = $ym->getSelectHtml();
    return $output;
}
