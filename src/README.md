Инструкция для работы с модулем.

После установки модуля необходимо:
1. В чанке формы заказа, в списке способов оплаты указать [[!YooMoney?&action=`showMethods`]]
Т.е., например, в чанке shopOrderForm будет:
```
<select name="payment" style="width:200px;">
    <option value="При получении" [[!+fi.payment:FormItIsSelected=`При получении`]]>При получении</option>
    [[!YooMoney? &action=`showMethods` ]]
</select>
```
Так же, если используется оплата с помощью ЮKassa с использованием Альфа-Клика или Киви,
необходимо добавить в чанк оформления платежа чанк [[$YooMoney]]
Т.е., например, в чанке shopOrderForm будет:
```
<select name="payment" style="width:200px;">
    <option value="При получении" [[!+fi.payment:FormItIsSelected=`При получении`]]>При получении</option>
        [[!YooMoney? &action=`showMethods` ]]
</select>
[[$YooMoney]]
```
2. В чанке страницы заказа, в список хуков FormIt добавить YooMoneyHook
Т.е., например, чанк orderform_page
```
[[!FormIt?
&hooks=`spam,shk_fihook,YooMoneyHook,email,FormItAutoResponder,redirect`
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
3. Создать 2 страницы: для успешно завершенного платежа и неуспешно завершенного. Указать их ID документа в параметрах сниппета YooMoney. 

4. Указать настройки магазина в параметрах сниппета YooMoney.

5. Для ЮKassa URL нотификации будет `https://<имя вашего домена>/assets/components/yoomoney/connector_result.php?notification=1`