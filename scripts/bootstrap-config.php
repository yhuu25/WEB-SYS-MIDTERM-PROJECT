<?php

declare(strict_types=1);

/**
 * Copies framework-provided starter configuration files that are not already
 * present. Project-specific files are never overwritten.
 */
$source = dirname(__DIR__) . '/vendor/codeigniter4/framework/app';
$target = dirname(__DIR__) . '/app';

if (! is_dir($source)) {
    fwrite(STDERR, "CodeIgniter framework templates were not found. Run composer install first.\n");
    exit(1);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST,
);

foreach ($iterator as $item) {
    $relative = substr($item->getPathname(), strlen($source) + 1);
    $destination = $target . DIRECTORY_SEPARATOR . $relative;

    if ($item->isDir()) {
        if (! is_dir($destination)) {
            mkdir($destination, 0775, true);
        }
        continue;
    }

    if (! file_exists($destination)) {
        if (! is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0775, true);
        }
        copy($item->getPathname(), $destination);
    }
}

echo "CodeIgniter starter configuration is ready.\n";
