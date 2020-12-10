
<tr id="yookassa-qiwi-block" style="display:none;">
    <td>Телефон, который привязан к Qiwi Wallet*:</td>
    <td>
        <input id="yookassa-qiwi-phone" name="qiwiPhone" size="30" class="textfield" type="text" value="[[!+fi.phone:default=`[[+modx.user.id:userinfo=`phone`]]`:ne=`0`:show]]" />
        <div>[[!+fi.error.yookassa-qiwi-phone]]</div>
    </td>
</tr>
<tr id="yookassa-alfa-block" style="display:none;">
    <td>Логин в Альфа-Клике*:</td>
    <td>
        <input id="yookassa-alfa-login" name="alfaLogin" size="30" class="textfield" type="text" value="" />
        <div>Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.</div>
    </td>
</tr>

<script type="text/javascript">
    jQuery(document).bind('ready',function(){
        jQuery('select[name="payment"]','#shopOrderForm').bind('change', function () {
            jQuery('#yookassa-qiwi-block').hide();
            jQuery('#yookassa-alfa-block').hide();
            if (this.value === 'qiwi') {
                jQuery('#yookassa-qiwi-block').show();
            } else if (this.value === 'alfabank') {
                jQuery('#yookassa-alfa-block').show();
            }
        });
        jQuery('input[type="submit"]','#shopOrderForm').bind('click', function (e) {
            var value = jQuery('select[name="payment"]','#shopOrderForm').val();
            if (value == 'qiwi') {
                var field = jQuery('#yookassa-qiwi-phone');
                var phone = field.val().replace(/[^\d]+/, '');
                if (phone == '') {
                    field.next().text('Укажите телефон');
                    if (e.stopPropagation) {
                        e.stopPropagation();
                    } else {
                        e.cancelBubble = true;
                    }
                    return false;
                }
            } else if (value == 'alfabank') {
                var field = jQuery('#yookassa-alfa-login');
                var login = field.val().replace(/\s+/, '');
                if (login == '') {
                    field.next().text('Укажите логин в Альфа-клике');
                    if (e.stopPropagation) {
                        e.stopPropagation();
                    } else {
                        e.cancelBubble = true;
                    }
                    return false;
                }
            }
        });
        SHK.selectDelivery(jQuery('select[name="shk_delivery"]','#shopOrderForm').val());
        jQuery('select[name="shk_delivery"]','#shopOrderForm').bind('change',function(){
            SHK.selectDelivery(jQuery(this).val());
        });
    });
</script>