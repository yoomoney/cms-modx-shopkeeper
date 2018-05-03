
<tr id="ya-kassa-qiwi-block" style="display:none;">
    <td>Телефон, который привязан к Qiwi Wallet*:</td>
    <td>
        <input id="ya-kassa-qiwi-phone" name="qiwiPhone" size="30" class="textfield" type="text" value="[[!+fi.phone:default=`[[+modx.user.id:userinfo=`phone`]]`:ne=`0`:show]]" />
        <div>[[!+fi.error.ya-kassa-qiwi-phone]]</div>
    </td>
</tr>
<tr id="ya-kassa-alfa-block" style="display:none;">
    <td>Логин в Альфа-Клике*:</td>
    <td>
        <input id="ya-kassa-alfa-login" name="alfaLogin" size="30" class="textfield" type="text" value="" />
        <div>Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.</div>
    </td>
</tr>
<tr id="ya-billing-fio-block" style="display:none;">
    <td>ФИО плательщика*:</td>
    <td>
        <input name="ya-billing-fio" size="30" class="textfield" type="text" value="[[!+fi.fullname:default=`[[+modx.user.id:userinfo=`fullname`]]`:ne=`0`:show]]" />
        <div>[[!+fi.error.ya-billing-fio]]</div>
    </td>
</tr>

<script type="text/javascript">
    jQuery(document).bind('ready',function(){
        jQuery('select[name="payment"]','#shopOrderForm').bind('change', function () {
            jQuery('#ya-kassa-qiwi-block').hide();
            jQuery('#ya-kassa-alfa-block').hide();
            if (this.value == '4') {
                jQuery('#ya-billing-fio-block').show();
            } else {
                jQuery('#ya-billing-fio-block').hide();
                if (this.value === 'qiwi') {
                    jQuery('#ya-kassa-qiwi-block').show();
                } else if (this.value === 'alfabank') {
                    jQuery('#ya-kassa-alfa-block').show();
                }
            }
        });
        jQuery('input[type="submit"]','#shopOrderForm').bind('click', function (e) {
            var value = jQuery('select[name="payment"]','#shopOrderForm').val();
            if (value == '4') {
                var field = jQuery('#ya-billing-fio-block input');
                var parts = field.val().trim().split(/\s+/);
                if (parts.length == 3) {
                    field.val(parts.join(' '));
                    field.next().text('');
                    return true;
                }
                field.next().text('Укажите фамилию, имя и отчество плательщика');
                if (e.stopPropagation) {
                    e.stopPropagation();
                } else {
                    e.cancelBubble = true;
                }
                return false;
            } else if (value == 'qiwi') {
                var field = jQuery('#ya-kassa-qiwi-phone');
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
                var field = jQuery('#ya-kassa-alfa-login');
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