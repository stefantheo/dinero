<?php

use App\Enums\SpendTypeEnum;
use App\Enums\VisibilityStatusEnum;

return [
    'title' => 'Cheltuieli',
    'title_singular' => 'Cheltuiala',
    'actions' => [
        'deposit' => 'Depune',
        'withdraw' => 'Retrage',
    ],
    'fields' => [
        'name' => 'Nume',
        'amount' => 'Cont',
        'target_date' => 'Data obiectivului',
        'currency_code' => 'Codul monedei',
        'color' => 'Culoare',
        'wallet' => 'Portofel',
        'from_wallet' => 'De la portofel',
        'to_wallet' => 'La portofel',
        'goal' => 'Obiectiv',
        'target_amount' => 'Suma obiectiv',
        'balance' => 'Balanta',
        'target_from' => 'de la',
        'target_until' => 'pana la',
    ],
];
