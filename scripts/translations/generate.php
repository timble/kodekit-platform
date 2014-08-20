#!/usr/bin/env php
<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Script;

require 'vendor/autoload.php';
require 'bootstrap.php';

$generator = new Script\TranslationsGenerator($argv);

file_put_contents('php://stdout', $generator->getTranslations());
file_put_contents('php://stderr', $generator->getErrorMessage());