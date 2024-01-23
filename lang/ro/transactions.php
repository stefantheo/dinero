<?php

use App\Enums\SpendTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\VisibilityStatusEnum;

return [
    'title' => 'Transactions',
    'title_singular' => 'Transaction',
    'fields' => [
        'amount' => 'Amount',
        'confirmed' => 'Confirmed',
        'category' => 'Category',
        'account' => 'Account',
        'happened_at' => 'Happened',
        'description' => 'Description',
        'type' => 'Type',
        'wallet' => 'Wallet',
        'from_wallet' => 'From Wallet',
        'to_wallet' => 'To Wallet',
        'note' => 'Note',
        'attachment' => 'Attachment',
    ],
    'types' => [
        TransactionTypeEnum::DEPOSIT->value   => [
            'id' => TransactionTypeEnum::DEPOSIT->value,
            'label' => 'Depozit',
            'description' => 'Depoziteaza in portofel',
        ],
        TransactionTypeEnum::WITHDRAW->value  => [
            'id' => TransactionTypeEnum::WITHDRAW->value,
            'label' => 'Retragere',
            'description' => 'Retrage din portofel',
        ],
        TransactionTypeEnum::TRANSFER->value  => [
            'id' => TransactionTypeEnum::TRANSFER->value,
            'label' => 'Transfer',
            'description' => 'Transfera dintr-un portofel in altul',
        ],
        TransactionTypeEnum::PAYMENT->value  => [
            'id' => TransactionTypeEnum::PAYMENT->value,
            'label' => 'Plata',
            'description' => 'Plata dintr-un portofel in altul',
        ],
    ]
];
