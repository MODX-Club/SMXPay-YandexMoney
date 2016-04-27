{if !in_array($smarty.request.action, ['checkOrder', 'paymentAviso'])}
    {$modx->sendRedirect($modx->makeUrl($modx->getOption('ShopModxYandexKassa.success_resource_id')))}
{/if}
{$ok = $modx->setLogLevel(2)}
{$ok = $modx->setLogTarget('FILE')}
{processor action="yandexmoney/web/public/action" ns="smxpayyandexmoney" params=['request' => (array)$smarty.request] assign=result}
{$result.message}
