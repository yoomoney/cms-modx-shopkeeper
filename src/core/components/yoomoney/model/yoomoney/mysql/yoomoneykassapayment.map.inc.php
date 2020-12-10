<?php
$xpdo_meta_map['YooMoneyKassaPayment']= array (
  'package' => 'yoomoney',
  'version' => NULL,
  'table' => 'yoomoney_payments',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'order_id' => NULL,
    'payment_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'payment_id' => 
    array (
      'dbtype' => 'char',
      'precision' => '36',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'columns' => 
      array (
        'order_id' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'UNIQUE_PAYMENT_ID' => 
    array (
      'alias' => 'UNIQUE_PAYMENT_ID',
      'primary' => false,
      'unique' => true,
      'columns' => 
      array (
        'payment_id' => 
        array (
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
