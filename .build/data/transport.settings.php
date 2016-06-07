<?php

$settings = array();

/* */
$setting_name = 'ShopModxYandexKassa.SC_ID';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => '',
 'description' => 'scid (номер витрины)',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

/* */
$setting_name = 'ShopModxYandexKassa.SECURITY_TYPE';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => 'MD5',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

/* */
$setting_name = 'ShopModxYandexKassa.SHOP_ID';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => '',
 'description' => 'shopId (идентификатор магазина)',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

/* */
$setting_name = 'ShopModxYandexKassa.SHOP_PASSWORD';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => '',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

/* */
$setting_name = 'ShopModxYandexKassa.bill_serv_id';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => '',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

/* */
$setting_name = 'ShopModxYandexKassa.pay_server';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => 'https://money.yandex.ru/eshop.xml',
 'description' => 'Тестовый сервер https://demomoney.yandex.ru/eshop.xml Боевой сервер https://money.yandex.ru/eshop.xml',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

/* */
$setting_name = 'ShopModxYandexKassa.success_resource_id';
$setting = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
 'key' => $setting_name,
 'value' => '{{site_start}}',
 'description' => 'ID ресурса для редиректа при успешной оплате',
 'xtype' => 'textfield',
 'namespace' => NAMESPACE_NAME,
 'area' => 'default',
), '', true, true);

$settings[] = $setting;
//

unset($setting, $setting_name);

return $settings;
