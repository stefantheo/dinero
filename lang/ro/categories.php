<?php

use App\Enums\SpendTypeEnum;

return [
    'title' => 'Categorii',
    'title_singular' => 'Categoria',
    'fields' => [
        'name' => 'Nume',
        'type' => 'Tip',
        'icon' => 'Icon',
        'color' => 'Culoare',
        'monthly_balance' => 'Buget lunar',
        'total' => 'Total',
        'is_visible' => 'Este vizibil?',
        'is_visible_help_text' => 'Ignoră această categorie la soldul total și nu o afișa în lista de tranzacții',
    ],
    'types' => [
        SpendTypeEnum::INCOME->value   => [
            'id' => SpendTypeEnum::INCOME->value,
            'label' => 'Income',
            'description' => 'your income category',
        ],
        SpendTypeEnum::EXPENSE->value   => [
            'id' => SpendTypeEnum::EXPENSE->value,
            'label' => 'Expense',
            'description' => 'your expense category',
        ],
    ]
];
