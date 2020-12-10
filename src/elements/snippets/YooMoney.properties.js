[{
    "name": "description_template",
    "desc": "Описание платежа",
    "xtype": "textfield",
    "options": [],
    "value": "Оплата заказа №%id%",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "account",
    "desc": "Номер кошелька ЮMoney (для физлиц)",
    "xtype": "textfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Номер кошелька ЮMoney (для физлиц)",
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
    "name": "method_tinkoff_bank",
    "desc": "Интернет-банк Тинькофф",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Интернет-банк Тинькофф",
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
    "desc": "Использовать способ оплаты - электронная валюта ЮMoney?",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Использовать способ оплаты - электронная валюта ЮMoney?",
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
        "text": "Юридическое лицо (выбор способа оплаты на стороне ЮKassa)",
        "value": "3",
        "name": "Юридическое лицо (выбор способа оплаты на стороне ЮKassa)"
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
    "desc": "Идентификатор вашего магазина в ЮKassa (ShopID)",
    "xtype": "textfield",
    "options": [],
    "value": "",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Идентификатор вашего магазина в ЮKassa (ShopID)",
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
    "menu": null
}, {
    "name": "tax_id",
    "desc": "Ставка по умолчанию.",
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
        "text": "20%",
        "value": "4",
        "name": "20%"
    }, {
        "text": "Расчетная ставка 10/110",
        "value": "5",
        "name": "Расчетная ставка 10/110"
    }, {
        "text": "Расчетная ставка 20/120",
        "value": "6",
        "name": "Расчетная ставка 20/120"
    }],
    "value": "4",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Ставка по умолчанию будет в чеке, если в карточке товара не указана другая ставка.",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_send_check",
    "desc": "Отправлять в ЮKassa данные для чеков (54-ФЗ)",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Отправлять в ЮKassa данные для чеков (54-ФЗ) НДС",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_payment_subject",
    "desc": "Признак предмета расчета",
    "xtype": "list",
    "options": [{
        "text": "Товар (commodity)",
        "value": "commodity",
        "name": "Товар (commodity)"
    }, {
        "text": "Подакцизный товар (excise)",
        "value": "excise",
        "name": "Подакцизный товар (excise)"
    }, {
        "text": "Работа (job)",
        "value": "job",
        "name": "Работа (job)"
    }, {
        "text": "Услуга (service)",
        "value": "service",
        "name": "Услуга (service)"
    }, {
        "text": "Ставка в азартной игре (gambling_bet)",
        "value": "gambling_bet",
        "name": "Ставка в азартной игре (gambling_bet)"
    }, {
        "text": "Выигрыш в азартной игре (gambling_prize)",
        "value": "gambling_prize",
        "name": "Выигрыш в азартной игре (gambling_prize)"
    }, {
        "text": "Лотерейный билет (lottery)",
        "value": "lottery",
        "name": "Лотерейный билет (lottery)"
    }, {
        "text": "Выигрыш в лотерею (lottery_prize)",
        "value": "lottery_prize",
        "name": "Выигрыш в лотерею (lottery_prize)"
    }, {
        "text": "Результаты интеллектуальной деятельности (intellectual_activity)",
        "value": "intellectual_activity",
        "name": "Результаты интеллектуальной деятельности (intellectual_activity)"
    }, {
        "text": "Платеж (payment)",
        "value": "payment",
        "name": "Платеж (payment)"
    }, {
        "text": "Агентское вознаграждение (agent_commission)",
        "value": "agent_commission",
        "name": "Агентское вознаграждение (agent_commission)"
    }, {
        "text": "Имущественные права (property_right)",
        "value": "property_right",
        "name": "Имущественные права (property_right)"
    }, {
        "text": "Внереализационный доход (non_operating_gain)",
        "value": "non_operating_gain",
        "name": "Внереализационный доход (non_operating_gain)"
    }, {
        "text": "Страховой сбор (insurance_premium)",
        "value": "insurance_premium",
        "name": "Страховой сбор (insurance_premium)"
    }, {
        "text": "Торговый сбор (sales_tax)",
        "value": "sales_tax",
        "name": "Торговый сбор (sales_tax)"
    }, {
        "text": "Курортный сбор (resort_fee)",
        "value": "resort_fee",
        "name": "Курортный сбор (resort_fee)"
    }, {
        "text": "Несколько вариантов (composite)",
        "value": "composite",
        "name": "Несколько вариантов (composite)"
    }, {
        "text": "Другое (another)",
        "value": "another",
        "name": "Другое (another)"
    }],
    "value": "commodity",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Признак предмета расчета",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_payment_mode",
    "desc": "Признак способа расчета",
    "xtype": "list",
    "options": [{
        "text": "Полная предоплата (full_prepayment)",
        "value": "full_prepayment",
        "name": "Полная предоплата (full_prepayment)"
    }, {
        "text": "Частичная предоплата (partial_prepayment)",
        "value": "partial_prepayment",
        "name": "Частичная предоплата (partial_prepayment)"
    }, {
        "text": "Аванс (advance)",
        "value": "advance",
        "name": "Аванс (advance)"
    }, {
        "text": "Полный расчет (full_payment)",
        "value": "full_payment",
        "name": "Полный расчет (full_payment)"
    }, {
        "text": "Частичный расчет и кредит (partial_payment)",
        "value": "partial_payment",
        "name": "Частичный расчет и кредит (partial_payment)"
    }, {
        "text": "Кредит (credit)",
        "value": "credit",
        "name": "Кредит (credit)"
    }, {
        "text": "Выплата по кредиту (credit_payment)",
        "value": "credit_payment",
        "name": "Выплата по кредиту (credit_payment)"
    }],
    "value": "full_prepayment",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Признак способа расчета",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_shipping_payment_subject",
    "desc": "Признак предмета расчета",
    "xtype": "list",
    "options": [{
        "text": "Товар (commodity)",
        "value": "commodity",
        "name": "Товар (commodity)"
    }, {
        "text": "Подакцизный товар (excise)",
        "value": "excise",
        "name": "Подакцизный товар (excise)"
    }, {
        "text": "Работа (job)",
        "value": "job",
        "name": "Работа (job)"
    }, {
        "text": "Услуга (service)",
        "value": "service",
        "name": "Услуга (service)"
    }, {
        "text": "Ставка в азартной игре (gambling_bet)",
        "value": "gambling_bet",
        "name": "Ставка в азартной игре (gambling_bet)"
    }, {
        "text": "Выигрыш в азартной игре (gambling_prize)",
        "value": "gambling_prize",
        "name": "Выигрыш в азартной игре (gambling_prize)"
    }, {
        "text": "Лотерейный билет (lottery)",
        "value": "lottery",
        "name": "Лотерейный билет (lottery)"
    }, {
        "text": "Выигрыш в лотерею (lottery_prize)",
        "value": "lottery_prize",
        "name": "Выигрыш в лотерею (lottery_prize)"
    }, {
        "text": "Результаты интеллектуальной деятельности (intellectual_activity)",
        "value": "intellectual_activity",
        "name": "Результаты интеллектуальной деятельности (intellectual_activity)"
    }, {
        "text": "Платеж (payment)",
        "value": "payment",
        "name": "Платеж (payment)"
    }, {
        "text": "Агентское вознаграждение (agent_commission)",
        "value": "agent_commission",
        "name": "Агентское вознаграждение (agent_commission)"
    }, {
        "text": "Имущественные права (property_right)",
        "value": "property_right",
        "name": "Имущественные права (property_right)"
    }, {
        "text": "Внереализационный доход (non_operating_gain)",
        "value": "non_operating_gain",
        "name": "Внереализационный доход (non_operating_gain)"
    }, {
        "text": "Страховой сбор (insurance_premium)",
        "value": "insurance_premium",
        "name": "Страховой сбор (insurance_premium)"
    }, {
        "text": "Торговый сбор (sales_tax)",
        "value": "sales_tax",
        "name": "Торговый сбор (sales_tax)"
    }, {
        "text": "Курортный сбор (resort_fee)",
        "value": "resort_fee",
        "name": "Курортный сбор (resort_fee)"
    }, {
        "text": "Несколько вариантов (composite)",
        "value": "composite",
        "name": "Несколько вариантов (composite)"
    }, {
        "text": "Другое (another)",
        "value": "another",
        "name": "Другое (another)"
    }],
    "value": "commodity",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Признак предмета расчета",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_shipping_payment_mode",
    "desc": "Признак способа расчета для доставки",
    "xtype": "list",
    "options": [{
        "text": "Полная предоплата (full_prepayment)",
        "value": "full_prepayment",
        "name": "Полная предоплата (full_prepayment)"
    }, {
        "text": "Частичная предоплата (partial_prepayment)",
        "value": "partial_prepayment",
        "name": "Частичная предоплата (partial_prepayment)"
    }, {
        "text": "Аванс (advance)",
        "value": "advance",
        "name": "Аванс (advance)"
    }, {
        "text": "Полный расчет (full_payment)",
        "value": "full_payment",
        "name": "Полный расчет (full_payment)"
    }, {
        "text": "Частичный расчет и кредит (partial_payment)",
        "value": "partial_payment",
        "name": "Частичный расчет и кредит (partial_payment)"
    }, {
        "text": "Кредит (credit)",
        "value": "credit",
        "name": "Кредит (credit)"
    }, {
        "text": "Выплата по кредиту (credit_payment)",
        "value": "credit_payment",
        "name": "Выплата по кредиту (credit_payment)"
    }],
    "value": "full_prepayment",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Признак способа расчета",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_send_second_receipt",
    "desc": "Второй чек",
    "xtype": "combo-boolean",
    "options": [],
    "value": true,
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Второй чек",
    "area": "",
    "area_trans": "",
    "menu": null
}, {
    "name": "yookassa_send_second_receipt_status",
    "desc": "Формировать второй чек при переходе заказа в статус",
    "xtype": "list",
    "options": [{
        "text": "Новый",
        "value": "0",
        "name": "Новый"
    }, {
        "text": "Принят к оплате",
        "value": "1",
        "name": "Принят к оплате"
    }, {
        "text": "Отправлен",
        "value": "2",
        "name": "Отправлен"
    }, {
        "text": "Выполнен",
        "value": "3",
        "name": "Выполнен"
    }, {
        "text": "Отменен",
        "value": "4",
        "name": "Отменен"
    }, {
        "text": "Оплата получена",
        "value": "5",
        "name": "Оплата получена"
    }],
    "value": "0",
    "lexicon": "",
    "overridden": false,
    "desc_trans": "Формировать второй чек при переходе заказа в статус",
    "area": "",
    "area_trans": "",
    "menu": null
}]