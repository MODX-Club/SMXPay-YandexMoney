<?php

$plugins = array();

$plugin_name = 'sampleSMXPayYandexMoneyEventHandler';
$content = getSnippetContent($sources['plugins'].$plugin_name.'.plugin.php');

if (!empty($content)) {

    /*
     * New plugin
     */

    $plugin = $modx->newObject('modPlugin');
    $plugin->set('id', 1);
    $plugin->set('name', $plugin_name);
    $plugin->set('description', $plugin_name.'_desc');
    $plugin->set('plugincode', $content);

    $plugin->fromArray(array(
        'static' => 1,
        'static_file' => $sources['plugins'].$plugin_name.'.plugin.php',
    ));
    $modx->log(modX::LOG_LEVEL_INFO, $plugin_name.' plugin was added.');

    /* add plugin events */
    $events = array();

    $events['OnHandleRequest'] = $modx->newObject('modPluginEvent');
    $events['OnHandleRequest']->fromArray(array(
        'event' => 'OnHandleRequest',
        'priority' => 0,
        'propertyset' => 0,
    ), '', true, true);

    $plugin->addMany($events, 'PluginEvents');

    $modx->log(xPDO::LOG_LEVEL_INFO,  count($events).' plugin events were added.');
    flush();

    $plugins[] = $plugin;
}

unset($plugin, $events, $plugin_name, $content);

return $plugins;
