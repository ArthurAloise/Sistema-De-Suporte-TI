<?php

return [
    // Alvo de resolução por prioridade (horas corridas; ajuste se quiser horas úteis)
    'priority_targets_hours' => [
        'muito alta' => 4,   // P1
        'alta'       => 8,   // P2
        'media'      => 24,  // P3
        'baixa'      => 72,  // P4
    ],

    // Regras por TYPE (mais específico)
    'priority_by_type' => [
        'Problema de rede'   => 'muito alta',
        'Acesso ao Sistema'  => 'muito alta',
        'Bug no sistema'     => 'alta',
        'Sistema'            => 'alta',
        'E-mail'             => 'alta',
        'Backup'             => 'media',
        'Acesso Wi-Fi'       => 'media',
        'Impressão'          => 'baixa',
        'Outros'             => 'baixa',
    ],

    // Fallback por CATEGORY (se o type não decidir)
    'priority_by_category' => [
        'Rede'           => 'muito alta',
        'Infraestrutura' => 'muito alta',
        'Segurança'      => 'alta',
        'Software'       => 'alta',
        'Acesso/Conta'   => 'alta',
        'Hardware'       => 'media',
        'Telefonia'      => 'media',
        'Backup'         => 'media',
        'Impressoras'    => 'baixa',
        'Outros'         => 'baixa',
    ],

    'defaults' => [
        'priority' => 'media',
    ],
];
