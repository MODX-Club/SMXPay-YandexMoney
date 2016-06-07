<?php

class SMXPayYandexMoney extends modProcessor
{
    public $events = array(
        'beforeProcess' => 'OnSMXPayYandexMoneyBeforeOrderProcess',
        'checkOrder' => 'OnSMXPayYandexMoneyCheckOrderAction',
        'paymentAviso' => 'OnSMXPayYandexMoneyPaymentAvisoAction',
    );

    public function process()
    {
    }
}
