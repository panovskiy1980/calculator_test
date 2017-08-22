<?php

/**
 * @author Ilya Panovskiy <panovskiy1980@gmail.com>
 */

return [
    'calculator' => [
        'cache_class' => 'Calculator\Classes\SimpleFileCache',
        'path_to_cache_folder' => 'calc_cache',
        'default_file_prefix' => 'calc_cache',
    ]
];