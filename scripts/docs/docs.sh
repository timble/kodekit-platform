#!/bin/bash
###
# Generates documentation for Koowa
###

# Where is Koowa located
SRC_DIR=/path/to/trunk

# The actual code
CODE_DIR=${SRC_DIR}/code/plugins/system/koowa

# Examples
EXAMPLES_DIR=${SRC_DIR}/trunk

# Target for the generated docs
TGT_DIR=/path/to/docs

# Project Title
TITLE="Nooku Framework - Trunk"

# Output information to use separated by ','. Format: output:converter:templatedir
OUTPUT=HTML:frames:DOM/earthli 

# Generate syntax-colored source code
SOURCE=on

# File(s) that will be ignored, multiple separated by ','.  Wildcards * and ? are ok
IGNORE=chart/,filter/ascii/data/

# No output, for cron jobs
QUIET=off

svn update $SRC_DIR
rm -rf ${TGT_DIR}/*
phpdoc -d $CODE_DIR -ed $EXAMPLES_DIR -t $TGT_DIR -i $IGNORE -q $QUIET -ti "$TITLE" -o $OUTPUT -s $SOURCE -dn "Koowa" -dc "Koowa"
