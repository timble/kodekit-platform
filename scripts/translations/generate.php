#!/usr/bin/env php
<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Script;

require 'vendor/autoload.php';
require 'bootstrap.php';

$generator = new Script\TranslationsGenerator($argv);

file_put_contents('php://stdout', $generator->getTranslations());
file_put_contents('php://stderr', $generator->getErrorMessage());