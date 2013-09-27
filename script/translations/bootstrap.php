<?php
/**
 * Created by JetBrains PhpStorm.
 * User: arunasmazeika
 * Date: 26/09/13
 * Time: 16:18
 * To change this template use File | Settings | File Templates.
 */

use Nooku\Library;

define('NOOKU_PATH', dirname(__FILE__) . '/../..');

ini_set('xdebug.max_nesting_level', 2000);

$old = error_reporting();
error_reporting($old & ~E_STRICT);

// Boot Framework.
require_once '../../library/nooku.php';
\Nooku::getInstance();

$manager = Library\ObjectManager::getInstance();

spl_autoload_register(function ($class)
{
    $parts = explode(' ', strtolower(preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $class)));

    if (array_shift($parts) != 'nooku\script\translations') return;

    $file = dirname(__FILE__). '/'.implode('/', $parts) . '.php';

    if (file_exists($file))
    {
        require_once($file);
    }
});