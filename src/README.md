Инструкция для работы с модулем.

После установки модуля необходимо:
1. В чанке формы заказа, в списке способов оплаты указать [[!YandexMoney?&action=`showMethods`]]
Т.е., например, в чанке shopOrderForm будет:
```
<select name="payment" style="width:200px;">
    <option value="При получении" [[!+fi.payment:FormItIsSelected=`При получении`]]>При получении</option>
    [[!YandexMoney? &action=`showMethods` ]]
</select>
```
Так же, если используется оплата с помощью Яндекс.Платёжки или с помощью Яндекс.Кассы с использованием Альфа-Клика или Киви,
необходимо добавить в чанк оформления платежа чанк [[$YandexMoney]]
Т.е., например, в чанке shopOrderForm будет:
```
<select name="payment" style="width:200px;">
    <option value="При получении" [[!+fi.payment:FormItIsSelected=`При получении`]]>При получении</option>
        [[!YandexMoney? &action=`showMethods` ]]
</select>
[[$YandexMoney]]
```
2. В чанке страницы заказа, в список хуков FormIt добавить YandexMoneyHook
Т.е., например, чанк orderform_page
```
[[!FormIt?
&hooks=`spam,shk_fihook,YandexMoneyHook,email,FormItAutoResponder,redirect`
&submitVar=`order`
&emailTpl=`shopOrderReport`
&fiarTpl=`shopOrderReport`
&emailSubject=`В интернет-магазине "[[++site_name]]" сделан новый заказ`
&fiarSubject=`Вы сделали заказ в интернет-магазине "[[++site_name]]"`
&emailTo=`[[++emailsender]]`
&redirectTo=`25`
&validate=`address:required,fullname:required,email:email:required,phone:required`
&errTpl=`<br /><span class="error">[[+error]]</span>`
]]
```
3. Создать 2 страницы: для успешно завершенного платежа и неуспешно завершенного. Указать их ID документа в параметрах сниппета YandexMoney. 

4. Указать настройки магазина в параметрах сниппета YandexMoney.

5. Для Яндекс.Кассы URL нотификации будет `https://<имя вашего домена>/assets/components/yandexmoney/connector_result.php?notification=1`