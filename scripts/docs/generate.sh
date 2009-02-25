#!/bin/bash
###
# @version $Id$
# @package Koowa
# @copyright Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
# @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
# @link http://www.koowa.org
###
 
###
# Generates documentation for Koowa
###
SRC_DIR=../../code/plugins/system/koowa
TARGET_DIR=../../docs/api
 
echo -n "Clear the 'docs/api' directory? [y|n] "
read -s -e cleardocs
 
echo -n "Parse private elements? [y|n] "
read -s -e private
 
echo -n "Generate coloured source code? [y|n] "
read -s -e source
 
 
if [ "$cleardocs" == "y" ]; then
rm -r $TARGET_DIR/*
fi
 
if [ "$source" == "y" ]; then
source=on
else
source=off
fi
 
phpdoc -s $source -ti KoowaDocumentation -ue on -t $TARGET_DIR -d $SRC_DIR -dn Koowa -o HTML:frames:DOM/earthli 
 
firefox $TARGET_DIR/index.html