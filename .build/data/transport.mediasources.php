<?php

$mediaSources = array();

$params = array(
  'basePath' => array(
    'name' => 'basePath',
    'desc' => 'prop_file.basePath_desc',
    'type' => 'textfield',
    'options' => array(),
    'value' => 'core/components/'.PKG_PATH.'/',
    'lexicon' => 'core:source',
  ),
  'baseUrl' => array(
    'name' => 'baseUrl',
    'desc' => 'prop_file.baseUrl_desc',
    'type' => 'textfield',
    'options' => array(),
    'value' => 'core/components/'.PKG_PATH.'/',
    'lexicon' => 'core:source',
  ),
);

$mediaSource = $modx->newObject('sources.modMediaSource', array(
 'name' => PKG_NAME_LOWER.'_core',
 'class_key' => 'sources.modFileMediaSource',
 'description' => 'core/components/'.PKG_PATH.'/', //PKG_NAME_LOWER.' Core Source',
 'properties' => $params,
));

$mediaSources[] = $mediaSource;
unset($mediaSource);

$params = array(
  'basePath' => array(
    'name' => 'basePath',
    'desc' => 'prop_file.basePath_desc',
    'type' => 'textfield',
    'options' => array(),
    'value' => 'assets/components/'.PKG_PATH.'/',
    'lexicon' => 'core:source',
  ),
  'baseUrl' => array(
    'name' => 'baseUrl',
    'desc' => 'prop_file.baseUrl_desc',
    'type' => 'textfield',
    'options' => array(),
    'value' => 'assets/components/'.PKG_PATH.'/',
    'lexicon' => 'core:source',
  ),
);

$mediaSource = $modx->newObject('sources.modMediaSource', array(
 'name' => PKG_NAME_LOWER.'_assets',
 'class_key' => 'sources.modFileMediaSource',
 'description' => 'assets/components/'.PKG_PATH.'/', //PKG_NAME_LOWER.' Assets Source',
 'properties' => $params,
));

$mediaSources[] = $mediaSource;
unset($mediaSource);

$params = array(
  'basePath' => array(
    'name' => 'basePath',
    'desc' => 'prop_file.basePath_desc',
    'type' => 'textfield',
    'options' => array(),
    'value' => 'manager/components/'.PKG_PATH.'/',
    'lexicon' => 'core:source',
  ),
  'baseUrl' => array(
    'name' => 'baseUrl',
    'desc' => 'prop_file.baseUrl_desc',
    'type' => 'textfield',
    'options' => array(),
    'value' => 'manager/components/'.PKG_PATH.'/',
    'lexicon' => 'core:source',
  ),
);

$mediaSource = $modx->newObject('sources.modMediaSource', array(
 'name' => PKG_NAME_LOWER.'_manager',
 'class_key' => 'sources.modFileMediaSource',
 'description' => 'manager/components/'.PKG_PATH.'/', //PKG_NAME_LOWER.' Manager Source',
 'properties' => $params,
));

$mediaSources[] = $mediaSource;
unset($mediaSource);

return $mediaSources;
