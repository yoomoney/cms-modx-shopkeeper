<?php

/**
 * YooMoney for MODX Revo
 *
 * Payment
 *
 * @author YooMoney
 * @package yoomoney
 * @version 2.0.3
 */
use YooKassa\Client;
use YooKassa\Model\ConfirmationType;
use YooKassa\Model\Payment;
use YooKassa\Model\PaymentMethodType;
use YooKassa\Request\Payments\CreatePaymentRequest;
use YooMoneyModule\KassaSecondReceiptModel;

require_once YOOMONEY_PATH . 'autoload.php';

$modx->addPackage('yoomoney', YOOMONEY_PATH . 'model/');

class Yoomoney
{
    const MODULE_VERSION = '2.0.3';

    /** @var int Оплата через ЮMoney вообще не используется */
    const MODE_NONE = 0;

    /** @var int Оплата производится через ЮKassa */
    const MODE_KASSA = 1;

    /** @var int Оплата производится через ЮMoney */
    const MODE_MONEY = 2;

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

    private $_apiClient;

    public static $disabledMethods = array(
        PaymentMethodType::B2B_SBERBANK,
        PaymentMethodType::WECHAT,
        PaymentMethodType::WEBMONEY,
    );

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
        $mode = ($this->org_mode) ? '/eshop.xml' : '/quickpay/confirm.xml';
        return 'https://yoomoney.ru' . $mode;
    }

    public function checkPayMethod()
    {
        if ($this->mode == self::MODE_KASSA) {
            if (!$this->paymode) {
                if (in_array($this->pay_method, PaymentMethodType::getEnabledValues())) {
                    if ($this->pay_method === PaymentMethodType::QIWI) {
                        $phone = preg_replace('/[^\d]+/', '', $this->qiwiPhone);
                        if (empty($phone)) {
                            return false;
                        }
                        $this->qiwiPhone = $phone;
                    }
                    if ($this->pay_method === PaymentMethodType::ALFABANK) {
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
            return "<option value=''>ЮKassa (банковские карты, электронные деньги и другое)</option>";
        } elseif ($this->mode == self::MODE_KASSA) {
            if ($this->paymode) {
                return "<option value=''>ЮKassa (банковские карты, электронные деньги и другое)</option>";
            }
            $translations = array(
                PaymentMethodType::ALFABANK => array('ab', 'Оплата через Альфа-Клик'),
                PaymentMethodType::MOBILE_BALANCE => array('ma', 'Платеж со счета мобильного телефона'),
                PaymentMethodType::CASH => array('cash', 'Оплата наличными через кассы и терминалы'),
                PaymentMethodType::WEBMONEY => array('wm', 'Оплата из кошелька в системе WebMoney'),
                PaymentMethodType::QIWI => array('qw', 'Оплата через QIWI Wallet'),
                PaymentMethodType::SBERBANK => array('sb', 'Оплата через Сбербанк: оплата по SMS или SberPay'),
                PaymentMethodType::YOO_MONEY => array('ym', 'Оплата из кошелька ЮMoney'),
                PaymentMethodType::BANK_CARD => array('cards', 'Оплата с произвольной банковской карты'),
                PaymentMethodType::INSTALLMENTS => array('installments', 'Заплатить по частям'),
                PaymentMethodType::TINKOFF_BANK => array('tinkoff_bank', 'Интернет-банк Тинькофф'),
            );
            $list_methods = array();
            foreach (PaymentMethodType::getEnabledValues() as $paymentMethodCode) {
                if (!in_array($paymentMethodCode, self::$disabledMethods)) {
                    $list_methods[$paymentMethodCode] = array(
                        'key' => $translations[$paymentMethodCode][0],
                        'label' => $translations[$paymentMethodCode][1],
                    );
                }
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
                . 'assets/components/yoomoney/connector_result.php?return=1&order_id=' . $this->orderId;
            $payment = $this->createKassaPayment($order, $redirectUrl);
            if ($payment === null) {
                header('Location: ' . $root.'assets/components/yoomoney/connector_result.php?fail=1');
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
            /** @var \YooKassa\Model\Confirmation\ConfirmationRedirect $confirmation */
            $confirmation = $payment->getConfirmation();
            if ($confirmation !== null && $confirmation->getType() === ConfirmationType::REDIRECT) {
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
                        <input type="hidden" name="successUrl" value="'.$site_url.'assets/components/yoomoney/connector_result.php?success=1">';
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
     * @return \YooKassa\Model\PaymentInterface
     */
    private function createKassaPayment($order, $redirectUrl)
    {
        try {
            $builder = CreatePaymentRequest::builder();
            $builder->setClientIp($_SERVER['REMOTE_ADDR'])
                ->setAmount($this->orderTotal)
                ->setCapture(true)
                ->setDescription($this->createDescription($order))
                ->setMetadata(array(
                    'order_id' => $this->orderId,
                    'cms_name' => 'yoo_modx_revolution',
                    'module_version' => self::MODULE_VERSION,
                ));
            $confirmation = array(
                'type' => ConfirmationType::REDIRECT,
                'returnUrl' => $redirectUrl,
            );
            if (!$this->paymode) {
                if ($this->pay_method === PaymentMethodType::ALFABANK) {
                    $paymentMethod = array(
                        'type' => $this->pay_method,
                        'login' => $this->alfaLogin,
                    );
                    $confirmation = ConfirmationType::EXTERNAL;
                } elseif ($this->pay_method === PaymentMethodType::QIWI) {
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
            if (isset($this->config['yookassa_send_check']) && $this->config['yookassa_send_check']) {
                $this->addReceipt($builder, $order);
            }
            $request = $builder->build();
            if (isset($this->config['yookassa_send_check']) && $this->config['yookassa_send_check']) {
                $request->getReceipt()->normalize($request->getAmount());
            }
        } catch (\Exception $e) {
            self::log('error', 'Failed to create request: ' . $e->getMessage());
            return null;
        }

        try {
            $response = $this->getClient()->createPayment($request);
        } catch (\Exception $e) {
            self::log('error', 'Failed to create payment: ' . $e->getMessage());
            return null;
        }

        global $modx;

        /** @var YooMoneyKassaPayment $record */
        $record = $modx->getObject('YooMoneyKassaPayment', $this->orderId);
        self::log('debug', 'Fetching payment from db: ' . ($record === null ? 'null' : $record->get('payment_id')));
        if ($record === null) {
            self::log('debug', 'Create db payment');
            $record = $modx->newObject('YooMoneyKassaPayment');
            $record->set('order_id', $this->orderId);
        }
        $record->set('payment_id', $response->getId());
        $record->save();

        return $response;
    }

    /**
     * @param string $paymentId
     * @return \YooKassa\Model\PaymentInterface|null
     */
    public function getPaymentById($paymentId)
    {
        try {
            $payment = $this->getClient()->getPaymentInfo($paymentId);
        } catch (Exception $e) {
            self::log('error', 'Failed to find payment ' . $paymentId);
            $payment = null;
        }
        return $payment;
    }

    /**
     * @param \YooKassa\Model\PaymentInterface $payment
     * @param bool $fetch
     * @return \YooKassa\Model\PaymentInterface|null
     */
    public function capturePayment($payment, $fetch = true)
    {
        if ($fetch) {
            $payment = $this->getPaymentById($payment->getId());
            if ($payment === null) {
                return null;
            }
        }
        if ($payment->getStatus() === \YooKassa\Model\PaymentStatus::WAITING_FOR_CAPTURE) {
            try {
                $builder = \YooKassa\Request\Payments\Payment\CreateCaptureRequest::builder();
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
     * @param \YooKassa\Request\Payments\CreatePaymentRequestBuilder $builder
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
                    $builder->addReceiptItem($item['name'], $item['price'], $item['count'], $this->config['tax_id'],
                                             $this->config['yookassa_payment_mode'],
                                             $this->config['yookassa_payment_subject']);
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
            $builder->addReceiptShipping($shippingMethod, $shippingPrice, $this->config['tax_id'],
                                         $this->config['yookassa_shipping_payment_mode'],
                                         $this->config['yookassa_shipping_payment_subject']);
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

    /**
     * @param $callbackParams
     * @param $code
     */
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

    /**
     * оплачивает заказ
     */
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
     * @param SHKorder $order Инстанс заказа
     * @param $newStatus
     */
    public function hookSendSecondReceipt(SHKorder $order, $newStatus)
    {
        $orderInfo = array(
            'user_email' => $order->get('email'),
            'user_phone' => $order->get('phone'),
        );

        if (!$this->isNeedSecondReceipt($newStatus)) {
            return;
        }

        $paymentId   = $this->getPaymentIdByOrderId($order->get('id'));
        $paymentInfo = $this->getPaymentById($paymentId);

        $secondReceiptModel = new KassaSecondReceiptModel($paymentInfo, $orderInfo, $this->getClient());

        if (!$secondReceiptModel->sendSecondReceipt()) {
            return;
        }

        $sum = number_format($secondReceiptModel->getSettlementsSum(), 2, '.', ' ');
        $this->modx->lexicon->load('yoomoney:properties');
        $msg = $this->modx->lexicon('second_receipt_sent', array('sum' => $sum));
        $order->set('note', $msg);
        $order->save();
    }

    /**
     * Устанавливает новый статус исполнения заказа
     * @param SHKorder $order Инстанс изменяемого заказа
     * @param string $status Новый статус заказа
     * @return
     */
    public function updateOrderStatus(SHKorder $order, $status)
    {
        if ($status > 0) {
            $order->set('status', $status);
            return $order->save();
        }
    }

    /**
     * @param $newStatus
     * @return bool
     */
    private function isNeedSecondReceipt($newStatus)
    {
        $isSendReceipt       = $this->config['yookassa_send_check'];
        $isSendSecondReceipt = $this->config['yookassa_send_second_receipt'];
        $secondReceiptStatus = $this->config['yookassa_send_second_receipt_status'];

        if (!$isSendReceipt) {
            self::log('error','54 fz dont activate');
            return false;
        } elseif (!$isSendSecondReceipt) {
            self::log('error','Send second receipt dont activate');
            return false;
        } elseif ($secondReceiptStatus != $newStatus) {
            self::log('error','Incorrect order status, expected status = ' . $secondReceiptStatus
                . ', current status = ' . $newStatus);
            return false;
        }

        return true;
    }

    /**
     * Возвращает paymentId платежа
     * @param $orderId
     * @return bool | string
     */
    private function getPaymentIdByOrderId($orderId)
    {
        $sql  = 'SELECT payment_id FROM '.$this->modx->getTableName('YooMoneyKassaPayment').' WHERE `order_id` = :orderId';
        $stmt = $this->modx->prepare($sql);
        $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
        $stmt->execute();
        $dataSet = $stmt->fetch();
        $stmt->closeCursor();

        return empty($dataSet[0]) ? false : $dataSet[0];
    }

    /**
     * Преобразует шаблон назначения платежа в удобоваримую строку
     * @param string $template Шаблон назначения платежя
     * @param SHKorder $order Информация о заказе
     * @return string Строка для отправки в ЮMoney
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
     * @return Client
     */
    private function getClient()
    {
        if ($this->_apiClient === null) {
            $this->_apiClient = new Client();
            $this->_apiClient->setAuth($this->shopid, $this->password);
            $this->_apiClient->setLogger($this);

            $modxVersion = $this->modx->getVersionData();

            $userAgent = $this->_apiClient->getApiClient()->getUserAgent();
            $userAgent->setCms("MODX Revolution", $modxVersion['full_version']);
            $userAgent->setModule("yoomoney-cms-modx", self::MODULE_VERSION);
        }
        return $this->_apiClient;
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    public static function log($level, $message, $context = array())
    {
        if (!empty($context) && (is_array($context) || $context instanceof Traversable)) {
            $search = array();
            $replace = array();
            foreach ($context as $key => $value) {
                $search[] = '{' . $key . '}';
                $replace[] = $value;
            }
            $message = str_replace($search, $replace, $message);
        }
        $path = YOOMONEY_PATH . '/logs';
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
