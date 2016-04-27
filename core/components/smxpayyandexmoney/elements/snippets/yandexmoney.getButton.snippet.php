<?php

$fields = array_merge(
    array(),
    (array) $scriptProperties
);

$options = array();
$modx->getParser();
$maxIterations = intval($modx->getOption('parser_max_iterations', $options, 10));

//Сортировка значений внутри полей
foreach ($fields as $name => $val) {
    if (is_array($val)) {
        usort($val, 'strcasecmp');
        $fields[$name] = $val;
    } else {
        // На случай наличия в значениях MODX-тегов, парсим их сразу, иначе нарушится цифровая подпись
        $modx->parser->processElementTags('', $fields[$name], true, false, '[[', ']]', array(), $maxIterations);
        $modx->parser->processElementTags('', $fields[$name], true, true, '[[', ']]', array(), $maxIterations);
    }
}

// Формирование HTML-кода платежной формы в Smarty-шаблоне
$modx->smarty->assign('fields', $fields);

return $modx->smarty->fetch('yandexmoney/button.tpl');
