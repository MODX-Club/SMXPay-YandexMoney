<?php
if ($modx->event->name == $modx->SMXPayYandexMoney->events['checkOrder']) {
    
    $modx->log(1, print_r($processor->getProperties()), '', "Plugin");
    
    return $modx->event->_output = true;
