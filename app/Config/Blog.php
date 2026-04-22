<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Blog extends BaseConfig
{
    public string $defaultSlug = 'tailo';

    public array $hostMap = [
        'localhost' => 'tailo',
        '127.0.0.1' => 'tailo',
    ];
}