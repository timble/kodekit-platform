#!/usr/bin/env php
<?php

require 'vendor/autoload.php';
require 'languagefilegenerator.php';

$dir = $argv[1];

$generator = new LanguageFileGenerator($dir);

file_put_contents('php://stdout', $generator->getTranslationFile());
file_put_contents('php://stderr', $generator->getErrorMessage());