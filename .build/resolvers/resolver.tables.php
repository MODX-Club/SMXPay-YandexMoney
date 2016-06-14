<?php

$pkgName = 'SMXPayYandexMoney';
$pkgNameLower = strtolower($pkgName);

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $modx = &$object->xpdo;

        if (!$ns = $modx->getObject('modNamespace', 'smxpayyandexmoney')) {
            $core_path = $modx->getOption('core_path')."components/{$pkgNameLower}/";
        } else {
            $core_path = $ns->getCorePath();
        }

        $modx->addPackage($pkgName, $core_path.'model/');

        $manager = $modx->getManager();
        $modx->setLogLevel(modX::LOG_LEVEL_ERROR);

        // adding xpdo objects
        # $manager->createObjectContainer('SamplePackageObject');

        $modx->setLogLevel(modX::LOG_LEVEL_INFO);

      break;
    case xPDOTransport::ACTION_UPGRADE:
      break;
  }
}

return true;
