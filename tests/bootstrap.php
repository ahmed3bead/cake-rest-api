<?php
/**
 * Test suite bootstrap for CakeRestApi.
 *
 */

/**
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */
$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);

    throw new Exception("Cannot find the root of the application, unable to run tests");
};
$root = $findRoot(__FILE__);
unset($findRoot);

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT', $root);
define('APP_DIR', 'App');
define('WEBROOT_DIR', 'webroot');
define('APP', ROOT . '/tests/App/');
define('CONFIG', ROOT . '/tests/config/');
define('WWW_ROOT', ROOT . DS . WEBROOT_DIR . DS);
define('TESTS', ROOT . DS . 'tests' . DS);
define('TMP', ROOT . DS . 'tmp' . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

$loader = new \Cake\Core\ClassLoader;
$loader->register();

$loader->addNamespace('App', APP);

require ROOT . '/vendor/autoload.php';
require CORE_PATH . 'config/bootstrap.php';

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

$TMP = new \Cake\Filesystem\Folder(TMP);
$TMP->create(TMP . 'cache/models', 0777);
$TMP->create(TMP . 'cache/persistent', 0777);
$TMP->create(TMP . 'cache/views', 0777);

Cake\Core\Configure::write('debug', true);

Cake\Core\Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => 'src',
    'webroot' => 'webroot',
    'www_root' => APP . 'webroot',
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
    'paths' => [
        'plugins' => [APP . 'Plugin' . DS],
        'templates' => [APP . 'Template' . DS]
    ]
]);

$cache = [
    'default' => [
        'engine' => 'File'
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'rest_myapp_cake_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+10 seconds'
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'rest_my_app_cake_model_',
        'path' => CACHE . 'models/',
        'serialize' => 'File',
        'duration' => '+10 seconds'
    ],
    '_cake_routes_' => [
        'className' => 'File',
        'prefix' => 'rest_my_app_cake_routes_',
        'path' => CACHE,
        'serialize' => 'File',
        'duration' => '+10 seconds'
    ]
];

Cake\Cache\Cache::setConfig($cache);

Cake\Core\Configure::write('Session', [
    'defaults' => 'php'
]);

Cake\Log\Log::setConfig([
    'debug' => [
        'engine' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'levels' => ['notice', 'info', 'debug'],
        'file' => 'debug',
    ],
    'error' => [
        'engine' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        'file' => 'error',
    ]
]);

Cake\Core\Plugin::load('Rest', ['path' => ROOT . DS, 'autoload' => true, 'bootstrap' => true]);

// Ensure default test connection is defined
if (!getenv('db_dsn')) {
    putenv('db_dsn=sqlite:///:memory:');
}

Cake\Datasource\ConnectionManager::setConfig('test', [
    'url' => getenv('db_dsn'),
    'timezone' => 'UTC'
]);
