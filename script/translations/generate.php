#!/usr/bin/env php
<?php

use Nooku\Script;

require 'vendor/autoload.php';
require 'bootstrap.php';

$generator = new Script\TranslationsGenerator($argv);

file_put_contents('php://stdout', $generator->getTranslations());
file_put_contents('php://stderr', $generator->getErrorMessage());