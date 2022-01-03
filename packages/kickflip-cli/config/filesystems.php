<?php

declare(strict_types=1);

return [
    'default' => 'local',
    'disks' => [
        'app' => [
            'driver' => 'local',
            'root' => getenv('HOME') . DIRECTORY_SEPARATOR . '.kickflip-cli',
        ],
        'kickflip' => [
            'driver' => 'local',
            'root' => storage_path(),
        ],
        'local' => [
            'driver' => 'local',
            'root' => getcwd(),
        ],
        'arbitrary' => [
            'driver' => 'local',
            'root' => getcwd(),
        ],
    ],
];
