<?php

namespace Config;

use App\Filters\AuthFilter;
use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf' => CSRF::class,
        'forcehttps' => ForceHTTPS::class,
        'invalidchars' => InvalidChars::class,
        'pagecache' => PageCache::class,
        'performance' => PerformanceMetrics::class,
        'secureheaders' => SecureHeaders::class,
        'auth' => AuthFilter::class,
    ];

    public array $required = [
        'before' => ['forcehttps', 'pagecache'],
        'after' => ['pagecache', 'performance'],
    ];

    public array $globals = [
        'before' => ['csrf'],
        'after' => ['secureheaders'],
    ];

    public array $methods = [];
    public array $filters = [];
}
