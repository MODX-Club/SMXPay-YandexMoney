<?php

/*
Процессор, определяющий по запрошенному действию какой процессор выполнять
*/

require_once dirname(dirname(__FILE__)) . '/payments/create.class.php';

class modYandexmoneyWebPublicActionProcessor extends modYandexmoneyWebPaymentsCreateProcessor
{
}

return 'modYandexmoneyWebPublicActionProcessor';
