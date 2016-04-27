{block payment-params}
    {$pay_form_header = $pay_form_header|default:'Оплата заказа'}
    {$pay_sum = $pay_sum|default:500}
    {$allow_edit_sum = $allow_edit_sum|default:true}
    {$phone_required = $phone_required|default:false}
    {$email_required = $email_required|default:false}
    {$email_field_name = $email_field_name|default:'cps_email'}
    {$order_type = $order_type|default:''}
    {$order_number = $order_number|default:''}
{/block}
{*
    <!-- Пример в кодировке UTF-8 (обязательно используйте именно эту кодировку для взаимодействия с Яндекс.Кассой)
    Внимание! Это только пример. Для того, чтобы он работал, обязательно пропишите в нем shopId и scid, который мы присылаем в письме на ваш контактный e-mail.
    Кроме того вам надо реализовать программную часть для CheckOrderURL и AvisoURL.
    -->
*}

{*
    <!--

    Ниже перечислены доступные формы оплаты.

    Перечисленные методы оплаты могут быть доступны в боевой среде после подписания Договора.

    Какие именно методы доступны для вашего Договора, вы можете уточнить у своего персонального менеджера.



    AB - Альфа-Клик

    AC - банковская карта

    GP - наличные через терминал

    MA - MasterPass

    MC - мобильная коммерция

    PB  -интернет-банк Промсвязьбанка

    PC - кошелек Яндекс.Денег

    SB - Сбербанк Онлайн

    WM - кошелек WebMoney



    <input name="paymentType" value="GP" type="radio">Оплата по коду через терминал<br>

    <input name="paymentType" value="WM" type="radio">Оплата cо счета WebMoney<br>

    <input name="paymentType" value="AB" type="radio">Оплата через Альфа-Клик<br>

    <input name="paymentType" value="PB" type="radio">Оплата через Промсвязьбанк<br>

    <input name="paymentType" value="MA" type="radio">Оплата через MasterPass<br>

    -->
*}

{*
    Документация: <a href="https://money.yandex.ru/doc.xml?id=526537">https://money.yandex.ru/doc.xml?id=526537</a>

    <!-- Тестовый яндекс.кошелек

    (прежде чем использовать тестовый яндекс.кошелек, обязательно войдите в https://demomoney.yandex.ru с указанными ниже логином и паролем)



    Test-for-yamoney@yandex.ru

    Пароль для входа: yamoney

    Платежный пароль: testforyamoney

    -->



    <!-- Тестовая банковская карта



    Номер карты: 4268 0337 0354 5624

    Действует до: любая дата в будущем

    Имя и фамилия владельца: любое имя латиницей, например, IVAN DEMIDOV

    Код CVV: 123

    Email: свой e-mail



    Код CVC: 123

    -->



    <!--

    Пример ответа на POST запрос к CheckURL

    <?xml version="1.0" encoding="utf-8"?>

    <checkOrderResponse performedDatetime="2015-06-23T08:59:16+03:00" code="1" shopId="" invoiceId=""/>



    Пример ответа на paymentAviso при успехе обработки:

    <?xml version="1.0" encoding="UTF-8"?>

    <paymentAvisoResponse performedDatetime ="2011-05-04T20:38:11.000+04:00" code="0" invoiceId="1234567" shopId="13"/>

    -->



    <!--

    EPS и PNG файлы яндекс.кошелька

    https://money.yandex.ru/partners/doc.xml?id=522991



    EPS и PNG других платежных методов

    https://money.yandex.ru/doc.xml?id=526421

    -->
*}


{block payment-outer}
    {block payment-pre}

        <h2>{$pay_form_header}</h2>

    {/block}{block payment-form}

        {block payment-form-statistics}

            {*
                {$q = $modx->newQuery('Payment', [
                    "paysystem_id"  => $modx->getOption('ShopModxYandexKassa.bill_serv_id')
                ])}


                {$total = $modx->getCount('Payment', $q)}

                {$ok = $q->select([
                    "sum(`sum`) as `sum`"
                ])}

                {$sum = $modx->getValue($q->prepare())}
                <div class="alert alert-info">
                    Всего в поддержку было выполнено <strong>{$total} {$total|spell:"платеж":"платежа":"платежей"}</strong> на общую сумму <strong>{(float)$sum} {$sum|spell:"рубль":"рубля":"рублей"}</strong>.
                </div>
            *}

        {/block}

        <form class="payment" action="{$modx->getOption('ShopModxYandexKassa.pay_server')}" method="POST">
            {block payment-form-default-fields}
                {*
                <!-- ОБЯЗАТЕЛЬНАНЫЕ ПОЛЯ (все параметры яндекс.кассы регистрозависимые) -->
                *}


                <input type="hidden" name="shopId" value="{$modx->getOption('ShopModxYandexKassa.SHOP_ID')}">
                <input type="hidden" name="scid" value="{$modx->getOption('ShopModxYandexKassa.SC_ID')}">
                <input type="hidden" name="CustomerNumber" size="64" value="{$modx->user->id}">
                <input type="hidden" name="order_type" value="{$order_type}" />

                {*<!-- CustomerNumber -- до 64 символов; идентификатор плательщика в ИС Контрагента.

                В качестве идентификатора может использоваться номер договора плательщика, логин плательщика и т.п.

                Возможна повторная оплата по одному и тому же идентификатору плательщика.

                sum -- сумма заказа в рублях.

                -->*}
            {/block}

            {*<!-- необязательные поля (все параметры яндекс.кассы регистрозависимые) -->*}
            {if $fields}
                {foreach $fields as $key => $val}
                    {if is_array($val)}
                        {foreach $val as $value}
                        {/foreach}
                    {else}
                        <input name="{$key}" type="hidden" value="{$val}"/>
                    {/if}
                {/foreach}
            {/if}

            {block payment-form-inner}

                <input name="orderNumber" value="{$order_number}" type="hidden"/>

                <div class="payment__group">
                    <label>Сумма (руб.) {if $allow_edit_sum}<span class="text-info">Сумму можно менять</span>{/if}</label>
                    <input class="payment__control" type="text" name="sum" size="64" value="{$pay_sum}" {if !$allow_edit_sum}readonly{/if}>
                </div>

                <div class="payment__group">
                    <label>Телефон</label>
                    <input class="payment__control" name="cps_phone" value="" placeholder="+7-123-456-78-90" type="text" {if $phone_required}required{/if}/>
                </div>

                <div class="payment__group">
                    <label>Емейл</label>
                    <input class="payment__control" name="{$email_field_name}" value="{$modx->user->Profile->email}" placeholder="Ваш емейл" type="text" {if $email_required}required{/if}/>
                </div>

                {*<!-- Внимание! Для тестирования в ДЕМО-среде доступны только два метода оплаты: тестовый Яндекс.Кошелек и Тестовая банковская карта -->*}
                <div class="payment__group">
                    <label>Способ оплаты</label>

                    <select class="payment__control" name="paymentType">
                        <option value="PC">Яндекс.Деньги (яндекс кошелек)</option>
                        <option value="AC">Банковская карта</option>
                        {*<option value="WM">WebMoney</option>
                        <option value="GP">Наличными через кассы и терминалы</option>
                        <option value="AB">Альфа-банк (Альфа-Клик)</option>
                        <option value="PB">Промсвязьбанк</option>
                        <option value="MA">MasterPass</option>*}
                        {*
                            Это только при личном контакте
                            <option value="MP">Мобильный терминал (mPOS)</option>
                        *}
                        {*<option value="QW">QIWI Wallet</option>
                        <option value="SB">Сбербанк (Онлайн или по СМС)</option>*}
                    </select>
                </div>

                <input type="submit"/>

            {/block}
        </form>
    {/block}{block payment-post}

    {/block}
{/block}
