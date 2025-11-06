<?php

// config/import-export.php
return [
    'max_file_size' => 2048, // 2MB in KB
    'allowed_file_types' => [
        'xls',
        'xlsx', 
        'csv'
    ],
    'default_date_format' => 'Y-m-d',
    'export' => [
        'format' => 'xlsx',
        'timeout' => 300, // 5 minutes
    ],
    'import' => [
        'chunk_size' => 1000,
        'validate_before_import' => true,
        'rollback_on_error' => true,
    ],
];