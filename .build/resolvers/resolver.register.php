<?php

$pkgName = 'SMXPayYandexMoney';
$pkgNameLower = strtolower($pkgName);

if ($object->xpdo) {
    $modx = &$object->xpdo;
    $modelPath = $modx->getOption("{$pkgNameLower}.core_path", null, $modx->getOption('core_path')."components/{$pkgNameLower}/").'model/';

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        if ($modx instanceof modX) {
            $modx->addExtensionPackage($pkgName, "[[++core_path]]components/{$pkgNameLower}/model/", array(
                'serviceName' => $pkgName,
                'serviceClass' => 'services.'.$pkgName,
            ));
            $modx->log(xPDO::LOG_LEVEL_INFO, 'Package added');

            $tmp_obj_data = array('name' => 'YandexMoney');
            if (!$modx->getObject('Paysystem', $tmp_obj_data)) {
                $paysystem = $modx->newObject('Paysystem');
                $paysystem->fromArray($tmp_obj_data);

                if (!$paysystem->save()) {
                    $modx->log(modX::LOG_LEVEL_ERROR, "Can't create paysystem object");
                } else {
                    $ss = $modx->getObject('modSystemSetting', 'ShopModxYandexKassa.bill_serv_id');
                    $ss->set('value', $paysystem->get('id'));

                    if (!$ss->save()) {
                        $modx->log(modX::LOG_LEVEL_ERROR, "Can't update system setting");
                    } else {
                        $modx->log(modX::LOG_LEVEL_INFO, 'Paysystem setting was successfully updated.');
                    }
                }
            }
        }
        break;

    case xPDOTransport::ACTION_UNINSTALL:
      if ($modx instanceof modX) {
          $modx->removeExtensionPackage($pkgName);
      }
      break;
  }
}

return true;
