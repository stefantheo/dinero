<?php

use App\Enums\DebtActionTypeEnum;
use App\Enums\DebtTypeEnum;

return [
    'title' => 'Datorii',
    'title_singular' => 'Datorie',
    'actions' => [
        'debt_transaction' => 'Tranzactie',
    ],
    'fields' => [
        'name' => 'Nume',
        'type' => 'Tip',
        'amount' => 'Suma',
        'description' => 'Descriere',
        'start_at' => 'Data inceput',
        'color' => 'Culoare',
        'wallet' => 'Portofel',
        'initial_wallet' => 'Portofel initial',
        'happened_at' => 'Data tranzactie',
        'debt' => 'Datorie',
        'action_type' => 'Tip tranzactie',
        'from_wallet' => 'Portofel sursa',
        'total_debt_amount' => 'Total Suma datorie',
    ],
    'types' => [
        DebtTypeEnum::PAYABLE->value => 'Plătibil',
        DebtTypeEnum::RECEIVABLE->value => 'De primit',
    ],
    'action_types' => [
        DebtTypeEnum::RECEIVABLE->value => [
            DebtActionTypeEnum::DEBT_COLLECTION->value => 'Colectare datorii',
            DebtActionTypeEnum::LOAN_INCREASE->value   => 'Crestere imprumut',
            DebtActionTypeEnum::LOAN_INTEREST->value   => 'Dobanda',
        ],
        DebtTypeEnum::PAYABLE->value => [
            DebtActionTypeEnum::REPAYMENT->value     => 'Rambursare',
            DebtActionTypeEnum::DEBT_INCREASE->value => 'Crestere datorie',
            DebtActionTypeEnum::DEBT_INTEREST->value => 'Dobanda',
        ],
    ]
];
