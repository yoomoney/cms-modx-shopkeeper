<?php

/**
 * YandexMoney for MODX Revo
 *
 * Payment
 *
 * @author YandexMoney
 * @package yandexmoney
 * @version 2.0.0
 */

use YandexCheckout\Model\Payment;

require_once YANDEXMONEY_PATH.'lib/autoload.php';

$modx->addPackage('yandexmoney', YANDEXMONEY_PATH . 'model/');

class Yandexmoney
{
    /** @var int Оплата через yandex.деньги вообще не используется */
    const MODE_NONE = 0;

    /** @var int Оплата производится через Яндекс.Кассу */
    const MODE_KASSA = 1;

    /** @var int Оплата производится через Яндекс.Деньги */
    const MODE_MONEY = 2;

    /** @var int Оплата производится через Яндекс.Платёжку */
    const MODE_BILLING = 3;

    /** @var int Какой способ оплаты используется, одна из констант MODE_XXX */
    private $mode;

    private $paymode;

    public $email = false;
    public $phone = false;

    public $test_mode;
    public $org_mode;

    public $orderId;
    public $orderTotal;
    public $userId;

    public $successUrl;
    public $failUrl;

    public $reciver;
    public $formcomment;
    public $short_dest;
    public $writable_targets = 'false';
    public $comment_needed = 'true';
    public $label;
    public $quickpay_form = 'shop';
    public $payment_type = '';
    public $targets;
    public $sum;
    public $comment;
    public $need_fio = 'true';
    public $need_email = 'true';
    public $need_phone = 'true';
    public $need_address = 'true';

    public $shopid;
    public $account;
    public $password;

    public $method_ym;
    public $method_cards;
    public $method_cash;
    public $method_wm;
    public $method_ab;
    public $method_sb;
    public $method_installments;

    public $pay_method;

    public $alfaLogin;
    public $qiwiPhone;

    public $debug_log;

    /** @var string Идентификатор магазина в Яндекс.Платёжке */
    public $ya_billing_id;

    /** @var string Описание платежа, заданное из админки */
    public $ya_billing_purpose;

    /** @var string ФИО плательщика, переданное из запроса пользователя */
    public $ya_billing_fio;

    private $_apiClient;

    function __construct(modX &$modx, $config = array())
    {
        $this->mode = self::MODE_NONE;
        switch ($config['mode']) {
            case 1:
                $this->mode = self::MODE_MONEY;
                break;
            case 2:
            case 3:
                $this->mode = self::MODE_KASSA;
                break;
            case 4:
                $this->mode = self::MODE_BILLING;
                break;
        }

        $this->org_mode = ($config['mode'] == 2 || $config['mode'] == 3);
        $this->paymode = (bool) ($config['mode'] == 3);

        if (isset($config) && is_array($config)){
            foreach ($config as $k=>$v){
                if ($k != 'mode') {
                    $this->$k = $v;
                }
            }
        }
        $this->modx =& $modx;
        $this->config = $config;

        if (empty($this->debug_log)) {
            $this->debug_log = false;
        } else {
            $this->debug_log = true;
        }
    }

    public function getFormUrl()
    {
        if ($this->mode != self::MODE_BILLING) {
            $demo = ($this->test_mode) ? 'demo' : '';
            $mode = ($this->org_mode) ? '/eshop.xml' : '/quickpay/confirm.xml';
            return 'https://' . $demo . 'money.yandex.ru' . $mode;
        }
        return 'https://money.yandex.ru/fastpay/confirm';
    }

    public function checkPayMethod()
    {
        if ($this->mode == self::MODE_BILLING) {
            $fio = explode(' ', $_POST['ya-billing-fio']);
            if (count($fio) != 3) {
                return false;
            }
            foreach ($fio as $index => $value) {
                $value = trim($value);
                if (empty($value)) {
                    return false;
                }
                $fio[$index] = $value;
            }
            $this->ya_billing_fio = implode(' ', $fio);
            return true;
        }
        if ($this->mode == self::MODE_KASSA) {
            if (!$this->paymode) {
                if (in_array($this->pay_method, \YandexCheckout\Model\PaymentMethodType::getEnabledValues())) {
                    if ($this->pay_method === \YandexCheckout\Model\PaymentMethodType::QIWI) {
                        $phone = preg_replace('/[^\d]+/', '', $this->qiwiPhone);
                        if (empty($phone)) {
                            return false;
                        }
                        $this->qiwiPhone = $phone;
                    }
                    if ($this->pay_method === \YandexCheckout\Model\PaymentMethodType::ALFABANK) {
                        $login = trim($this->alfaLogin);
                        if (empty($login)) {
                            return false;
                        }
                        $this->alfaLogin = $login;
                    }
                }
            }
            return true;
        }
        return (in_array($this->pay_method, array('PC','AC','MC','GP','WM','AB','SB','MA','PB','QW', 'installments')) || $this->paymode);
    }

    public function getSelectHtml()
    {
        $result = json_encode(array('mode' => $this->mode));
        if ($this->mode == self::MODE_MONEY) {
            return "<option value=''>Яндекс.Касса (банковские карты, электронные деньги и другое)</option>";
        } elseif ($this->mode == self::MODE_KASSA) {
            if ($this->paymode) {
                return "<option value=''>Яндекс.Касса (банковские карты, электронные деньги и другое)</option>";
            }
            $translations = array(
                \YandexCheckout\Model\PaymentMethodType::ALFABANK => array('ab', 'Оплата через Альфа-Клик'),
                \YandexCheckout\Model\PaymentMethodType::MOBILE_BALANCE => array('ma', 'Платеж со счета мобильного телефона'),
                \YandexCheckout\Model\PaymentMethodType::CASH => array('cash', 'Оплата наличными через кассы и терминалы'),
                \YandexCheckout\Model\PaymentMethodType::WEBMONEY => array('wm', 'Оплата из кошелька в системе WebMoney'),
                \YandexCheckout\Model\PaymentMethodType::QIWI => array('qw', 'Оплата через QIWI Wallet'),
                \YandexCheckout\Model\PaymentMethodType::SBERBANK => array('sb', 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн'),
                \YandexCheckout\Model\PaymentMethodType::YANDEX_MONEY => array('ym', 'Оплата из кошелька в Яндекс.Деньгах'),
                \YandexCheckout\Model\PaymentMethodType::BANK_CARD => array('cards', 'Оплата с произвольной банковской карты'),
                \YandexCheckout\Model\PaymentMethodType::INSTALLMENTS => array('installments', 'Заплатить по частям'),
            );
            $list_methods = array();
            foreach (\YandexCheckout\Model\PaymentMethodType::getEnabledValues() as $paymentMethodCode) {
                $list_methods[$paymentMethodCode] = array(
                    'key'   => $translations[$paymentMethodCode][0],
                    'label' => $translations[$paymentMethodCode][1],
                );
            }
            $output = '';
            foreach ($list_methods as $long_name => $method_desc) {
                $key = $method_desc['key'];
                $by_default = (in_array($key, array('ym', 'cards'))) ? true : $this->org_mode;
                if ($this->{'method_' . $key} == 1 && $by_default) {
                    $output .= '<option value="' . $long_name . '"';
                    if ($this->pay_method == $long_name) {
                        $output .= ' selected ';
                    }
                    $output .= '>' . $method_desc['label'] . '</option>';
                }
            }
            return $output;
        } elseif ($this->mode == self::MODE_BILLING) {
            return "<option value='4'>Яндекс.Платежка (банковские карты, кошелек)</option>";
        }
        return $result;
    }

    public function createFormHtml()
    {
        global $modx;

        /** @var SHKorder $order */
        $order = $modx->getObject('SHKorder',array('id' => $this->orderId));

        if ($this->mode == self::MODE_KASSA) {
            $redirectUrl = 'https://' . str_replace(array('http://', 'https://'), '' , $modx->config['site_url'])
                . 'assets/components/yandexmoney/connector_result.php?return=1&order_id=' . $this->orderId;
            $payment = $this->createKassaPayment($order, $redirectUrl);
            if ($payment === null) {
                header('Location: ' . $root.'assets/components/yandexmoney/connector_result.php?fail=1');
                exit();
            }
        }

        $html = '';

        $site_url = $modx->config['site_url'];
        $payType = ($this->paymode) ? '' : $this->pay_method;
        $addInfo = ($this->email!==false)?'<input type="hidden" name="cps_email" value="'.$this->email.'" >':'';
        $addInfo .= ($this->phone!==false)?'<input type="hidden" name="cps_phone" value="'.$this->phone.'" >':'';
        $html .= '<form method="POST" action="'.$this->getFormUrl().'"  id="paymentform" name = "paymentform">';
        if ($this->mode == self::MODE_KASSA) {
            /** @var \YandexCheckout\Model\Confirmation\ConfirmationRedirect $confirmation */
            $confirmation = $payment->getConfirmation();
            if ($confirmation !== null && $confirmation->getType() === \YandexCheckout\Model\ConfirmationType::REDIRECT) {
                $redirectUrl = $confirmation->getConfirmationUrl();
            }
            $html = '<script> document.location = "' . $redirectUrl . '"; </script>';
        } elseif ($this->mode == self::MODE_MONEY) {
            $html .= '  <input type="hidden" name="receiver" value="'.$this->account.'">
                       <input type="hidden" name="formcomment" value="Order '.$this->orderId.'">
                       <input type="hidden" name="short-dest" value="Order '.$this->orderId.'">
                       <input type="hidden" name="writable-targets" value="'.$this->writable_targets.'">
                       <input type="hidden" name="comment-needed" value="'.$this->comment_needed.'">
                       <input type="hidden" name="label" value="'.$this->orderId.'">
                       <input type="hidden" name="quickpay-form" value="'.$this->quickpay_form.'">
                       <input type="hidden" name="paymentType" value="'.$this->pay_method.'">
                       <input type="hidden" name="targets" value="Заказ '.$this->orderId.'">
                       <input type="hidden" name="sum" value="'.$this->orderTotal.'" data-type="number" >
                       <input type="hidden" name="comment" value="'.$this->comment.'" >
                       <input type="hidden" name="need-fio" value="'.$this->need_fio.'">
                       <input type="hidden" name="need-email" value="'.$this->need_email.'" >
                       <input type="hidden" name="need-phone" value="'.$this->need_phone.'">
                       <input type="hidden" name="need-address" value="'.$this->need_address.'">
                        <input type="hidden" name="successUrl" value="'.$site_url.'assets/components/yandexmoney/connector_result.php?success=1">';
        } elseif ($this->mode == self::MODE_BILLING) {
            $narrative = $this->parsePlaceholders($this->ya_billing_purpose, $order);
            $html .= '<input type="hidden" name="formId" value="'.$this->ya_billing_id.'" />
                <input type="hidden" name="narrative" value="'.htmlspecialchars($narrative).'" />
                <input type="hidden" name="fio" value="'.htmlspecialchars($this->ya_billing_fio).'" />
                <input type="hidden" name="sum" value="'.$this->orderTotal.'" />
                <input type="hidden" name="quickPayVersion" value="2" />';
            $this->updateOrderStatus($order, $this->config['ya_billing_status']);
        }
        if ($this->mode !== self::MODE_KASSA) {
            $html .= '<input type="hidden" name="cms_name" value="modx" >
                </form>
                <script type="text/javascript">
                    document.getElementById("paymentform").submit();
                </script>';
        }

        echo $html;
        exit;
    }

    /**
     * @param SHKorder $order
     * @param string $redirectUrl
     * @return \YandexCheckout\Model\PaymentInterface
     */
    private function createKassaPayment($order, $redirectUrl)
    {
        try {
            $builder = \YandexCheckout\Request\Payments\CreatePaymentRequest::builder();
            $builder->setClientIp($_SERVER['REMOTE_ADDR'])
                ->setAmount($this->orderTotal)
                ->setCapture(true)
                ->setDescription($this->createDescription($order))
                ->setMetadata(array(
                    'order_id' => $this->orderId,
                    'cms_name' => 'ya_api_modx_revolution',
                    'module_version' => '1.0.2',
                ));
            $confirmation = array(
                'type' => \YandexCheckout\Model\ConfirmationType::REDIRECT,
                'returnUrl' => $redirectUrl,
            );
            if (!$this->paymode) {
                if ($this->pay_method === \YandexCheckout\Model\PaymentMethodType::ALFABANK) {
                    $paymentMethod = array(
                        'type' => $this->pay_method,
                        'login' => $this->alfaLogin,
                    );
                    $confirmation = \YandexCheckout\Model\ConfirmationType::EXTERNAL;
                } elseif ($this->pay_method === \YandexCheckout\Model\PaymentMethodType::QIWI) {
                    $paymentMethod = array(
                        'type' => $this->pay_method,
                        'phone' => $this->qiwiPhone,
                    );
                } else {
                    $paymentMethod = $this->pay_method;
                }
                $builder->setPaymentMethodData($paymentMethod);
            }
            $builder->setConfirmation($confirmation);
            if (isset($this->config['ya_kassa_send_check']) && $this->config['ya_kassa_send_check']) {
                $this->addReceipt($builder, $order);
            }
            $request = $builder->build();
            if (isset($this->config['ya_kassa_send_check']) && $this->config['ya_kassa_send_check']) {
                $request->getReceipt()->normalize($request->getAmount());
            }
        } catch (\Exception $e) {
            $this->log('error', 'Failed to create request: ' . $e->getMessage());
            return null;
        }

        try {
            $response = $this->getClient()->createPayment($request);
        } catch (\Exception $e) {
            $this->log('error', 'Failed to create payment: ' . $e->getMessage());
            return null;
        }

        global $modx;

        /** @var YandexMoneyKassaPayment $record */
        $record = $modx->getObject('YandexMoneyKassaPayment', $this->orderId);
        $this->log('debug', 'Fetching payment from db: ' . ($record === null ? 'null' : $record->get('payment_id')));
        if ($record === null) {
            $this->log('debug', 'Create db payment');
            $record = $modx->newObject('YandexMoneyKassaPayment');
            $record->set('order_id', $this->orderId);
        }
        $record->set('payment_id', $response->getId());
        $record->save();

        return $response;
    }

    /**
     * @param string $paymentId
     * @return \YandexCheckout\Model\PaymentInterface|null
     */
    public function getPaymentById($paymentId)
    {
        try {
            $payment = $this->getClient()->getPaymentInfo($paymentId);
        } catch (Exception $e) {
            $this->log('error', 'Failed to find payment ' . $paymentId);
            $payment = null;
        }
        return $payment;
    }

    /**
     * @param \YandexCheckout\Model\PaymentInterface $payment
     * @param bool $fetch
     * @return \YandexCheckout\Model\PaymentInterface|null
     */
    public function capturePayment($payment, $fetch = true)
    {
        if ($fetch) {
            $payment = $this->getPaymentById($payment->getId());
            if ($payment === null) {
                return null;
            }
        }
        if ($payment->getStatus() === \YandexCheckout\Model\PaymentStatus::WAITING_FOR_CAPTURE) {
            try {
                $builder = \YandexCheckout\Request\Payments\Payment\CreateCaptureRequest::builder();
                $builder->setAmount($payment->getAmount());
                $request = $builder->build();
            } catch (Exception $e) {
                return null;
            }
            try {
                $response = $this->getClient()->capturePayment($request, $payment->getId());
            } catch (\Exception $e) {
                return null;
            }
        } else {
            $response = $payment;
        }
        return $response;
    }

    /**
     * @param \YandexCheckout\Request\Payments\CreatePaymentRequestBuilder $builder
     * @param SHKorder $order
     */
    private function addReceipt($builder, $order)
    {
        $builder->setReceiptEmail($order->_fields['email']);

        $shippingMethod = null;
        $shippingPrice = 0;
        if ($content = unserialize($order->_fields['content'])) {
            foreach ($content as $item) {
                if ($item['price'] > 0) {
                    $builder->addReceiptItem($item['name'], $item['price'], $item['count'], $this->config['tax_id']);
                } elseif (isset($item['tv_add']['shk_delivery'])) {
                    $shippingMethod = $item['tv_add']['shk_delivery'];
                }
            }
        }

        if (!empty($shippingMethod)) {
            foreach (unserialize($order->_fields['addit']) as $items) {
                foreach ($items as $item) {
                    if (isset($item[0]) && $item[0] === $shippingMethod) {
                        $shippingPrice = $item[1];
                    }
                }
            }
        }

        if ($shippingMethod && $shippingPrice > 0) {
            $builder->addReceiptShipping($shippingMethod, $shippingPrice, $this->config['tax_id']);
        }
    }

    public function checkSign($callbackParams)
    {
        if ($this->org_mode) {
            $string = $callbackParams['action'].';'.$callbackParams['orderSumAmount'].';'.$callbackParams['orderSumCurrencyPaycash'].';'.$callbackParams['orderSumBankPaycash'].';'.$callbackParams['shopId'].';'.$callbackParams['invoiceId'].';'.$callbackParams['customerNumber'].';'.$this->password;
            $md5 = strtoupper(md5($string));
            return ($callbackParams['md5']==$md5);
        } else {
            $string = $callbackParams['notification_type'].'&'.$callbackParams['operation_id'].'&'.$callbackParams['amount'].'&'.$callbackParams['currency'].'&'.$callbackParams['datetime'].'&'.$callbackParams['sender'].'&'.$callbackParams['codepro'].'&'.$this->password.'&'.$callbackParams['label'];
            $check = (sha1($string) == $callbackParams['sha1_hash']);
            if (!$check){
                header('HTTP/1.0 401 Unauthorized');
                return false;
            }
            return true;
        }
    }

    public function sendCode($callbackParams, $code)
    {
        if (!$this->org_mode) {
            if ($code === 0) {
                header('HTTP/1.0 200 OK');
            } else {
                header('HTTP/1.0 401 Unauthorized');
            }
            return;
        }
        header("Content-type: text/xml; charset=utf-8");
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <'.$callbackParams['action'].'Response performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'"/>';
        echo $xml;
    }

    /* оплачивает заказ */
    public function ProcessResult()
    {
        $callbackParams = $_POST;
        if ($this->checkSign($callbackParams)) {
            $order_id = ($this->org_mode)? intval($callbackParams["orderNumber"]):intval($callbackParams["label"]);
            if ($order_id) {
                $this->modx->addPackage('shopkeeper', MODX_CORE_PATH."components/shopkeeper/model/");
                $order = $this->modx->getObject('SHKorder',array('id'=>$order_id));
                $amount = number_format($order->get('price'),2,".",'');
                $pay_amount = number_format($callbackParams[($this->org_mode)?'orderSumAmount':'amount'], 2, '.', '');
                if ($pay_amount === $amount) {
                    if ($callbackParams['action'] == 'paymentAviso' || !$this->org_mode){
                        $order->set('status', 5);
                        $order->save();
                    }
                    $this->sendCode($callbackParams, 0);
                } else {
                    $this->sendCode($callbackParams, 100);
                }
            } else {
                $this->sendCode($callbackParams, 200);
            }
        } else {
            $this->sendCode($callbackParams, 1);
        }
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Преобразует шаблон назначения платежа в удобоваримую строку
     * @param string $template Шаблон назначения платежя
     * @param SHKorder $order Информация о заказе
     * @return string Строка для отправки в Яндекс.Деньги
     */
    private function parsePlaceholders($template, SHKorder $order)
    {
        $replace = array(
            '%order_id%' => $order->id,
        );
        foreach ($order->toArray() as $key => $value) {
            if (is_scalar($value)) {
                $replace['%' . $key . '%'] = $value;
            }
        }
        return strtr($template, $replace);
    }

    /**
     * Устанавливает новый статус исполнения заказа
     * @param SHKorder $order Инстанс изменяемого заказа
     * @param string $status Новый статус заказа
     */
    public function updateOrderStatus(SHKorder $order, $status)
    {

        if ($status > 0) {
            $order->set('status', $status);
            return $order->save();
        }
    }

    private function getClient()
    {
        if ($this->_apiClient === null) {
            $this->_apiClient = new \YandexCheckout\Client();
            $this->_apiClient->setAuth($this->shopid, $this->password);
            $this->_apiClient->setLogger($this);
        }
        return $this->_apiClient;
    }

    public function log($level, $message, $context = array())
    {
        if (!$this->debug_log) {
            return;
        }
        if (!empty($context) && (is_array($context) || $context instanceof Traversable)) {
            $search = array();
            $replace = array();
            foreach ($context as $key => $value) {
                $search[] = '{' . $key . '}';
                $replace[] = $value;
            }
            $message = str_replace($search, $replace, $message);
        }
        $path = YANDEXMONEY_PATH . '/logs';
        if (!file_exists($path)) {
            mkdir($path);
        }
        $fileName = $path . '/module.log';
        $fd = fopen($fileName, 'a');
        flock($fd, LOCK_EX);
        fwrite($fd, date(DATE_ATOM) . ' [' . $level . '] - ' . $message . PHP_EOL);
        flock($fd, LOCK_UN);
        fclose($fd);
    }

    /**
     * @param $order
     * @return string
     */
    private function createDescription($order)
    {
        $descriptionTemplate = !empty($this->config['description_template'])
            ? $this->config['description_template']
            : 'Оплата заказа №%id%';

        $replace  = array();
        $patterns = explode('%', $descriptionTemplate);
        foreach ($patterns as $pattern) {
            $value = $order->get($pattern);
            if (!is_null($value) && is_scalar($value)) {
                $replace['%'.$pattern.'%'] = $value;
            }
        }

        $description = strtr($descriptionTemplate, $replace);

        return (string)mb_substr($description, 0, Payment::MAX_LENGTH_DESCRIPTION);
    }
}
