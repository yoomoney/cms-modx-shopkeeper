[{
    "name": "account",
    "desc": "Номер кошелька Яндекс (для физлиц)",
    "xtype": "textfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Номер кошелька Яндекс (для физлиц)",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "fail_page_id",
    "desc": "ИД страницы \"неудачно завершенный платеж\"",
    "xtype": "numberfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "ИД страницы \"неудачно завершенный платеж\"",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_ab",
    "desc": "",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_cards",
    "desc": "Использовать метод оплаты - банковские карты VISA, MasterCard, Maestro",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Использовать метод оплаты - банковские карты VISA, MasterCard, Maestro",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_cash",
    "desc": "Использовать способ оплаты - наличными в кассах и терминалах партнеров (только для юрлиц)",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Использовать способ оплаты - наличными в кассах и терминалах партнеров (только для юрлиц)",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_qw",
    "desc": "Оплата через QIWI Wallet",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Оплата через QIWI Wallet",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_sb",
    "desc": "",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_wm",
    "desc": "Использовать метод оплаты - электронная валюта WebMoney (только для юрлиц)?",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Использовать метод оплаты - электронная валюта WebMoney (только для юрлиц)?",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_ym",
    "desc": "Использовать способ оплаты - электронная валюта Яндекс.Деньги?",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Использовать способ оплаты - электронная валюта Яндекс.Деньги?",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "method_installments",
    "desc": "Заплатить по частям",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Заплатить по частям",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "mode",
    "desc": "Ваш статус",
    "xtype": "list",
    "options": [{
        "text": "Физическое лицо",
        "value": "1",
        "name": "Физическое лицо"
    }, {
        "text": "Юридическое лицо (выбор способа оплаты на стороне магазина)",
        "value": "2",
        "name": "Юридическое лицо (выбор способа оплаты на стороне магазина)"
    }, {
        "text": "Юридическое лицо (выбор способа оплаты на стороне Яндекс.Кассы)",
        "value": "3",
        "name": "Юридическое лицо (выбор способа оплаты на стороне Яндекс.Кассы)"
    }],
    "value": "2",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Ваш статус",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "password",
    "desc": "Секретное слово (shopPassword) для обмена сообщениями",
    "xtype": "textfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Секретное слово (shopPassword) для обмена сообщениями",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "shopid",
    "desc": "Идентификатор вашего магазина в Яндекс.Деньгах (ShopID)",
    "xtype": "textfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Идентификатор вашего магазина в Яндекс.Деньгах (ShopID)",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "success_page_id",
    "desc": "ИД страницы \"удачно завершенный платеж\"",
    "xtype": "numberfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "ИД страницы \"удачно завершенный платеж\"",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "testmode",
    "desc": "Ставка по умолчанию.",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Ставка по умолчанию будет в чеке, если в карточке товара не указана другая ставка.",
    "area": "",
    "area_trans": "",
    "menu": null,
    {
        "name": "tax_id",
        "desc": "",
        "xtype": "list",
        "options": [{
            "text": "Без НДС",
            "value": "1",
            "name": "Без НДС"
        }, {
            "text": "10%",
            "value": "3",
            "name": "10%"
        }, {
            "text": "0%",
            "value": "2",
            "name": "0%"
        }, {
            "text": "18%",
            "value": "4",
            "name": "18%"
        }, {
            "text": "Расчётная ставка 10/110",
            "value": "5",
            "name": "Расчётная ставка 10/110"
        }, {
            "text": "Расчётная ставка 18/118",
            "value": "6",
            "name": "Расчётная ставка 18/118"
        }],
        "value": "4",
        "lexicon": "",
        "overridden": false,
        "desc_trans": "",
        "area": "",
        "area_trans": "",
        "menu": null
    },
    {
        "name": "ya_kassa_send_check",
        "desc": "Отправлять в Яндекс.Кассу данные для чеков (54-ФЗ)",
        "xtype": "combo-boolean",
        "options": [],
        "value": true,
        "lexicon": "",
        "overridden": false,
        "desc_trans": "Отправлять в Яндекс.Кассу данные для чеков (54-ФЗ) НДС",
        "area": "",
        "area_trans": "",
        "menu": null
    }
}]