<?php

define('YANDEX_MONEY_SDK_ROOT_PATH', YANDEXMONEY_PATH . 'lib/yandex-checkout-sdk/lib');
define('YANDEX_MONEY_PSR_LOG_PATH', YANDEXMONEY_PATH . 'lib/yandex-checkout-sdk/vendor/psr/log/Psr/Log');

function yandexMoneyLoadClass($className)
{
    if (strncmp('YandexCheckout', $className, 14) === 0) {
        $path = YANDEX_MONEY_SDK_ROOT_PATH;
        $length = 14;
    } elseif (strncmp('Psr\Log', $className, 7) === 0) {
        $path = YANDEX_MONEY_PSR_LOG_PATH;
        $length = 7;
    } else {
        return;
    }
    if (DIRECTORY_SEPARATOR === '/') {
        $path .= str_replace('\\', '/', substr($className, $length));
    } else {
        $path .= substr($className, $length);
    }
    $path .= '.php';
    if (file_exists($path)) {
        include $path;
    }
}

spl_autoload_register('yandexMoneyLoadClass');
