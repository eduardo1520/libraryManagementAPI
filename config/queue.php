<?php

return [
    'default' => env('QUEUE_CONNECTION', 'sync'),

    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default', // nome da conexão redis
            'queue' => 'default', // ou o nome da fila
            'retry_after' => 90, // tempo para tentar novamente um job que falhou
            'block_for' => null, // tempo de espera para block de fila (opcional)
        ],

        // Outras configurações de fila como 'database', 'beanstalkd', etc.
    ],

    'failed' => [
        'driver' => 'database',
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],
];
