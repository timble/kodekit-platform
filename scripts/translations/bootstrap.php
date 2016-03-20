<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

use Kodekit\Library;

define('KODEKIT_PATH', dirname(__FILE__) . '/../..');

ini_set('xdebug.max_nesting_level', 2000);

$old = error_reporting();
error_reporting($old & ~E_STRICT);

// Boot Framework.
require_once '../../library/kodekit.php';
\Kodekit::getInstance();

$manager = Library\ObjectManager::getInstance();

spl_autoload_register(function ($class)
{
    $parts = explode(' ', strtolower(preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $class)));

    if (array_shift($parts) != 'kodekit\script\translations') return;

    $file = dirname(__FILE__). '/'.implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});