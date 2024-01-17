<?php

use App\Enums\WalletTypeEnum;

return [
    'title' => 'Portofele',
    'title_singular' => 'Portofel',
    'actions' => [
        'refresh_balance' => 'Actualizeaza Balanta',
    ],
    'notifications' => [
        'balance_refreshed' => 'Balanta a fost actualizata cu succes',
    ],
    'fields' => [
        'name' => 'Denumire',
        'type' => 'Tip',
        'balance' => 'Balanta',
        'initial_balance' => 'Balanta Initiala',
        'credit_limit' => 'Limita Credit',
        'total_due' => 'Total Datorii',
        'currency_code' => 'Moneda',
        'description' => 'Descriere',
        'statement_day_of_month' => 'Ziua extrasului',
        'payment_due_day_of_month' => 'Ziua scadentă',
        'icon' => 'Icon',
        'color' => 'Culoare',
        'exclude' => [
            'title' => 'Exclude',
            'help_text' => 'Ignora aceasta balanta in totalul balantelor',

        ]
    ],
    'types' => [
        WalletTypeEnum::GENERAL->value => 'General',
        WalletTypeEnum::CREDIT_CARD->value => 'Credit Card',
    ]
];
