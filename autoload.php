<?php

define('SHOW_DEBUG', false);

$autoload = [
    'Workflow\Interface\ProcessableInterface' => 'Workflow/Interfaces/ProcessableInterface.php',
    'Workflow\Interface\StepInterface' => 'Workflow/Interfaces/StepInterface.php',
    'Workflow\Interface\StatusInterface' => 'Workflow/Interfaces/StatusInterface.php',
    'Workflow\Interface\StateInterface' => 'Workflow/Interfaces/StateInterface.php',
    'Workflow\Interfaces\\' => 'Workflow/Interfaces/',
    'Workflow\\' => 'Workflow/',
    'App\\' => 'app/',
];


function scan($source)
{
    $results = [];

    if (!is_dir($source)) {
        return [$source];
    }

    $files = scandir($source);
    foreach ($files as $key => $value) {
        $path = realpath($source.DIRECTORY_SEPARATOR.$value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else {
            if ($value != "." && $value != "..") {
                $subdir = scan($path);
                $results = array_merge($results, $subdir);
            }
        }
    }

    return $results;
}

$loaded = [];
foreach ($autoload as $ns => $source) {
    $files = scan($source);

    foreach ($files as $file) {

        if (isset($loaded[$file])) {
            continue;
        }

        if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
            if (SHOW_DEBUG) {
                echo "load: \t".$file."\n";
            }
            include_once $file;
            $loaded[$file] = true;
        }
    }
}