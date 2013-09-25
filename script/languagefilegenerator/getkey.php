#!/usr/bin/env php
<?php

require 'vendor/autoload.php';
require 'languagefilegenerator.php';

$key = $argv[1];
echo KObjectManager::getInstance()->getObject('com://admin/koowa.translator')->getKey($key)."\n";