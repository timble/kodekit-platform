#!/usr/bin/env php
<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Script;

require 'vendor/autoload.php';
require 'bootstrap.php';

$generator = new Script\TranslationsGenerator($argv);

file_put_contents('php://stdout', $generator->getTranslations());
file_put_contents('php://stderr', $generator->getErrorMessage());