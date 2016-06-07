<?php

$fields = array_merge(
    (array) $scriptProperties,
    (array) $modx->request->getParameters([], 'POST')
);

if ($modx->getOption('debug') && $modx->hasPermission('Debug')) {
    $modx->setLogLevel(2);
    $modx->setLogTarget('FILE');
    $modx->log(2, print_r($fields, 1));
}

if (!in_array($fields['action'], array('checkOrder', 'paymentAviso'))) {
    $modx->sendRedirect($modx->makeUrl($modx->getOption('ShopModxYandexKassa.success_resource_id')));
}

$result = $modx->runProcessor('yandexmoney/web/public/action',
    array('request' => $fields),
    array('processors_path' => $modx->getObject('modNamespace', 'smxpayyandexmoney')->getCorePath().'processors/')
);

return $result->getMessage();
