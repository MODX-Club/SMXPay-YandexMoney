<?php

#
# require_once MODX_CORE_PATH . 'components/basket/processors/web/payments/create.class.php';
#
# class modYandexmoneyWebPaymentsCreateProcessor extends modWebPaymentsCreateProcessor{

require_once MODX_CORE_PATH.'components/basket/processors/web/payments/create.class.php';

class modYandexmoneyWebPaymentsCreateProcessor extends modWebPaymentsCreateProcessor
{
    protected $SHOP_ID;
    protected $SHOP_PASSWORD;
    protected $SECURITY_TYPE;

    public function initialize()
    {
        $this->setProperties(array(
            'paysystem_id' => $this->modx->getOption('ShopModxYandexKassa.bill_serv_id'),
        ));

        $this->setProperties((array) $this->getProperty('request'));

        # if(!(int)$this->getProperty('order_id')){
        #     return 'Не был получен ID заказа';
        # }

        $this->SHOP_ID = $this->modx->getOption('ShopModxYandexKassa.SHOP_ID');
        $this->SHOP_PASSWORD = $this->modx->getOption('ShopModxYandexKassa.SHOP_PASSWORD');
        $this->SECURITY_TYPE = $this->modx->getOption('ShopModxYandexKassa.SECURITY_TYPE', null, 'MD5');

        # $this->setProperties(array(
        #     "SECURITY_TYPE" => $this->modx->getOption('ShopModxYandexKassa.SECURITY_TYPE', null, 'MD5'),
        # ));

        $this->setDefaultProperties(array(
            'action' => $this->getProperty('action'),
            'output_format' => 'XML',      // XML
        ));

        $this->setProperties(array(
            'order_id' => (int) $this->getProperty('orderNumber'),
            'sum' => $this->getProperty('orderSumAmount'),
            'owner' => $this->getProperty('CustomerNumber'),
            'paysys_invoice_id' => $this->getProperty('invoiceId'),
            # "order_type"    => $this->getProperty('EventID'),
        ));

        if (!$this->getProperty('hide_log')) {
            $this->modx->log(1, '[- '.__CLASS__.' -]');
            $this->modx->log(1, print_r($_SERVER, 1));
            $this->modx->log(1, json_encode($_SERVER));
            $this->modx->log(1, print_r($_REQUEST, 1));
            $this->modx->log(1, json_encode($_REQUEST));
        }

        return parent::initialize();
    }

    # protected function __process(){
    public function process()
    {
        $request = $this->getProperties();
        $action = $this->getProperty('action');
        $sum = (float) $this->getProperty('sum');
        $response = null;

        $order_type = (int) $this->getProperty('order_type');

        $this->modx->log(2, 'order_type: '.$order_type);

        /*
            Заказ демо-сайта ShopModxBox
        */
        if ($order_type === '1') {
            if ($sum < 100) {
                return $this->failure('Стоимость установки демо-сайта 100 рублей');
            } else {
                $this->modx->getObject('modUser', 2)->sendEmail('Email: '.$this->getProperty('email'), array(
                    'subject' => 'Новый запрос на установку демо-магазина ShopModxBox',
                ));
            }
        }

        /*
            Заказ демо-сайта NewsModxBox
        */
        if ($order_type === '2') {
            if ($sum < 300) {
                return $this->failure('Стоимость установки демо-сайта 300 рублей');
            } else {
                $this->modx->getObject('modUser', 2)->sendEmail('Email: '.$this->getProperty('email'), array(
                    'subject' => 'Новый запрос на установку демо-магазина NewsModxBox',
                ));
            }
        }

        /*
            Оплата мероприятия
        */

        elseif ($EventID = (int) $this->getProperty('EventID')) {
            $this->setProperty('order_type', $EventID);

            if (!$resource = $this->modx->getObject('modResource', $EventID)) {
                return $this->failure('Не был получен объект мероприятия');
            } elseif (
                $cost = $resource->getTVValue('cost')
                and $sum < $cost
            ) {
                return $this->failure("Оплачиваемая стоимость ниже стоимости мероприятия - {$cost} руб.");
            } elseif (
                $tickets_limit = $resource->getTVValue('tickets_limit')
                and $tickets_limit <= $this->modx->getCount($this->classKey, array(
                    'order_type' => $EventID,
                ))
            ) {
                return $this->failure('Извините! Все билеты распроданы.');
            }

            # return $this->failure('Debug');
        }

        /*
            В этом процессоре происходит обработка сразу двух типов запросов от Яндекса:
            1. checkOrder - проверка разрешение от ИМ на провод платежа
            2. paymentAviso - действие магазина в ответ на проведенный в Яндексе платеж.
        */

        # print $action;
        # exit;
        switch ($action) {

            case 'checkOrder':
                # $request = $this->getProperty('request');
                # $action = $this->getProperty('action');
                # $response = null;
                # if ($request['orderSumAmount'] < 100) {
                #     $response = $this->buildResponse($action, $request['invoiceId'], 100, "The amount should be more than 100 rubles.");
                # } else {
                #     $response = $this->buildResponse($action, $request['invoiceId'], 0);
                # }

                # die('sdfds');

                # return $this->success($this->buildResponse($action, $request['invoiceId'], 0));
                return $this->success('');
                break;

            case 'paymentAviso':
                # $request = $this->getProperty('request');
                # $action = $this->getProperty('action');
                # return $this->buildResponse($action, $request['invoiceId'], 0);

                break;

            default: return $this->failure('Wrong method');
        }

        # if ($action == 'checkOrder') {
        #     $response = $this->checkOrder($request);
        # } else {
        #     $response = $this->paymentAviso($request);
        # }
        # return $this->sendResponse($response);
        # $response = $this->buildResponse($action, $request['invoiceId'], 0);

        # return $this->buildResponse($action, $request['invoiceId'], 0);

        # return $this->failure('debug');
        # return parent::__process();
        return parent::process();
    }

    /*
        Проверяем подпись с робокассы
    */
    # protected function checkSignature(){
    #
    #     return true;
    # }

    # protected function getSuccessMessage(){
    #     return 'OK'.$this->getProperty('InvId');
    # }

#     public static function getInstance(modX &$modx,$className,$properties = array()) {
#
#         // Здесь мы имеем возможность переопределить реальный класс процессора
#         if(!empty($properties['pub_action']) && !self::$actualClassName){
#
#             switch($properties['pub_action']){
#
#                 default:
#
#                     # require MODX_CORE_PATH . 'components/modxclub/processors/web/forms/form.class.php';
#                     # self::$actualClassName = 'modWebFormsFormProcessor';
#                     ;
#             }
#         }
#
#         if(self::$actualClassName){
#             $className = self::$actualClassName;
#         }
#
#         return parent::getInstance($modx,$className,$properties);
#     }

    # public function process(){
    #     # $error = 'Действие не существует или не может быть выполнено';
    #     # $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__ . " - {$error}");
    #     # $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
    #     # return $this->failure($error);
    #
    #     if(!$this->getProperty('hide_log')){
    #
    #         $this->modx->log(1, "[- ". __CLASS__ ." -]");
    #         $this->modx->log(1, print_r($_SERVER, 1));
    #         $this->modx->log(1, json_encode($_SERVER));
    #         $this->modx->log(1, print_r($_REQUEST, 1));
    #         $this->modx->log(1, json_encode($_REQUEST));
    #     }
    #     else{
    #
    #         # print_r($this->properties);
    #     }
    #
    #
    #
    #     # return $this->success('fdgdfg');
    #
    #     return $this->processRequest();
    # }

    public function checkSignature()
    {
        $request = $this->getProperties();
        $action = $this->getProperty('action');

        $this->log('Start '.$action);
        $this->log('Security type '.$this->SECURITY_TYPE);

        # return false;

        switch ($this->SECURITY_TYPE) {

            case 'MD5':
                $this->log('Request: '.print_r($request, true));

                // If the MD5 checking fails, respond with "1" error code
                if (!$this->checkMD5($request)) {
                    # return $this->buildResponse($action, $request['invoiceId'], 1);
                    return 'Неверная подпись';
                    # return $this->failure($response);
                    # return $this->sendResponse($response);
                }
                # else{
                #
                #     die('sdfsdfds');
                # }

                break;

            case 'PKCS7':

                $this->log('Request: '.print_r($request, true));

                if (($request = $this->verifySign()) === null) {
                    # return $this->buildResponse($action, null, 200);
                    return 'Неверная подпись';
                    # return $this->failure($response);
                    # return $this->sendResponse($response);
                }
                break;

            default: return 'Wrong SECURITY_TYPE';
        }

        return true;
    }

    private function checkMD5($request)
    {
        $request = $this->getProperty('request');
        $action = $this->getProperty('action');

        $str = $request['action'].';'.
            $request['orderSumAmount'].';'.$request['orderSumCurrencyPaycash'].';'.
            $request['orderSumBankPaycash'].';'.$request['shopId'].';'.
            $request['invoiceId'].';'.$request['customerNumber'].';'.$this->SHOP_PASSWORD;
        $this->log('String to md5: '.$str);
        $md5 = strtoupper(md5($str));
        if ($md5 !== strtoupper($request['md5'])) {
            $this->log('Wait for md5:'.$md5.', recieved md5: '.$request['md5']);

            return false;
        }

        return true;
    }

    /**
     * Checking for sign when XML/PKCS#7 scheme is used.
     *
     * @return array if request is successful, returns key-value array of request params, null otherwise.
     */
    private function verifySign()
    {
        $request = $this->getProperty('request');
        $action = $this->getProperty('action');

        $descriptorspec = array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w'));
        $certificate = 'yamoney.pem';
        $process = proc_open('openssl smime -verify -inform PEM -nointern -certfile '.$certificate.' -CAfile '.$certificate, $descriptorspec, $pipes);
        if (is_resource($process)) {
            // Getting data from request body.
            $data = file_get_contents($this->settings->request_source); // "php://input"
            fwrite($pipes[0], $data);
            fclose($pipes[0]);
            $content = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $resCode = proc_close($process);
            if ($resCode !== 0) {
                return;
            } else {
                $this->log('Row xml: '.$content);
                $xml = simplexml_load_string($content);
                $array = json_decode(json_encode($xml), true);

                return $array['@attributes'];
            }
        }

        return;
    }

    # private function checkOrder($request) {
    #     $request = $this->getProperty('request');
    #     $action = $this->getProperty('action');
    #     $response = null;
    #     if ($request['orderSumAmount'] < 100) {
    #         $response = $this->buildResponse($action, $request['invoiceId'], 100, "The amount should be more than 100 rubles.");
    #     } else {
    #         $response = $this->buildResponse($action, $request['invoiceId'], 0);
    #     }
    #     return $response;
    # }

    /**
     * PaymentAviso request processing.
     *
     * @param array $request payment parameters
     *
     * @return string prepared response in XML format
     */
    # private function paymentAviso($request) {
    #     $request = $this->getProperty('request');
    #     $action = $this->getProperty('action');
    #     return $this->buildResponse($action, $request['invoiceId'], 0);
    # }

    private function buildResponse($functionName, $invoiceId, $result_code, $message = null)
    {
        $performedDatetime = $this->formatDate(new DateTime());
        $response = '<?xml version="1.0" encoding="UTF-8"?><'.$functionName.'Response performedDatetime="'.$performedDatetime.
            '" code="'.$result_code.'" '.($message !== null ? 'message="'.$message.'"' : '').' invoiceId="'.$invoiceId.'" shopId="'.$this->SHOP_ID.'"/>';

        return $response;
    }

    public static function formatDate(\DateTime $date)
    {
        $performedDatetime = $date->format('Y-m-d').'T'.$date->format('H:i:s').'.000'.$date->format('P');

        return $performedDatetime;
    }

    public static function formatDateForMWS(\DateTime $date)
    {
        $performedDatetime = $date->format('Y-m-d').'T'.$date->format('H:i:s').'.000Z';

        return $performedDatetime;
    }

    private function sendResponse($responseBody)
    {
        # $this->log("Response: " . $responseBody);
        header('HTTP/1.0 200');
        header('Content-Type: application/xml');

        return $responseBody;
    }

    public function log($msg, $level = null)
    {
        return parent::log($msg, xPDO::LOG_LEVEL_INFO);
    }

    public function failure($msg = '', $object = null)
    {
        # if($this->getProperty('output_format') == 'XML'){
        #     return $this->sendResponse($msg);
        # }
        # // else
        # return parent::failure($msg, $object);

        $this->modx->log(1, '[-'.__CLASS__.'-]');
        $this->modx->log(1, $msg);

        $request = $this->getProperty('request');
        $action = $this->getProperty('action');

        // https://money.yandex.ru/doc.xml?id=526537
        // Таблица 4.2.2.2. Коды результата обработки запроса checkOrder
        $code = !empty($object['code']) ? $object['code'] : 100;
        $response = $this->buildResponse($action, $request['invoiceId'], $code, $msg);

        return parent::failure($response, $object);
    }

    protected function getResponseError($message)
    {
        switch ($message) {

            case 'Order not exists':

                $message = 'Данный заказ не был найден';
                break;
        }

        return parent::getResponseError($message);
    }

    /**
     * Return a success message from the processor.
     *
     * @param string $msg
     * @param mixed  $object
     *
     * @return array|string
     */
    public function success($msg = '', $object = null)
    {
        # if($this->getProperty('output_format') == 'XML'){
        #     return $this->sendResponse($msg);
        # }
        # // else
        # return parent::success($msg, $object);

        $request = $this->getProperty('request');
        $action = $this->getProperty('action');

        // https://money.yandex.ru/doc.xml?id=526537
        // Таблица 4.2.2.2. Коды результата обработки запроса checkOrder
        $code = 0;
        $response = $this->buildResponse($action, $request['invoiceId'], $code, $msg);

        return parent::success($response, $object);
    }
}

return 'modYandexmoneyWebPaymentsCreateProcessor';
