<?php

return [

    /*
    |--------------------------------------------------------------------------
    | The list of order statuses 
    |--------------------------------------------------------------------------
    |
    | This array contains all the possible order statuses used.
    |
    */

    'order_statuses' => [
        '1' => ['tag' => 'to-order', 'label' => 'Da ordinare', 'color' => '#92e9ff', 'icon' => 'added'],
        '2' => ['tag' => 'added', 'label' => 'Da fare', 'color' => '#92e9ff', 'icon' => 'added'],
        '3' => ['tag' => 'in-cart', 'label' => 'Nel carrello', 'color' => '#92e9ff', 'icon' => 'added'],
        '4' => ['tag' => 'ordered', 'label' => 'Ordinato', 'color' => '#92e9ff', 'icon' => 'added'],
        '5' => ['tag' => 'prepared', 'label' => 'Preparato', 'color' => '#ffc677', 'icon' => 'prepared'],
        '6' => ['tag' => 'delivered', 'label' => 'Ritirato', 'color' => '#ff9600', 'icon' => 'delivered'],
        '7' => ['tag' => 'completed', 'label' => 'Pagato', 'color' => '#44c544', 'icon' => 'completed'],
        '8' => ['tag' => 'billed', 'label' => 'Preventivato', 'color' => '#44c544', 'icon' => 'billed'],
        '99' => ['tag' => 'canceled', 'label' => 'Cancellato', 'color' => '#92e9ff', 'icon' => 'canceled'],
    ],
];
