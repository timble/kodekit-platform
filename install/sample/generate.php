#!/usr/bin/env php
<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Sample data generator
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Sample_Data
 */

set_time_limit(0);

// Exit if script is not called from command line.
if(PHP_SAPI != 'cli')
{
    print 'This script is only meant to run at the command line.';
    exit(1);
}

// Define custom functions.
function pick_random(array $array)
{
    $key = array_rand($array);
    return !is_null($key) ? $array[$key] : null;
}

// Load application.
define('JPATH_APPLICATION', realpath(__DIR__.'/../../code/administrator'));
define('JPATH_BASE'       , JPATH_APPLICATION);
define('JPATH_ROOT'       , dirname(JPATH_APPLICATION));
define('JPATH_VENDOR'     , JPATH_ROOT.'/vendor' );
define('JPATH_SITES'      , JPATH_ROOT.'/sites');
define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_APPLICATION.'/bootstrap.php';

$application =  Library\ObjectManager::getInstance()->getObject('com:application.dispatcher');
$application->loadConfig(new Library\CommandContext());
$application->loadSession(new Library\CommandContext());

// Load Lorem Ipsum generator class.
require_once __DIR__.'/libraries/loremipsum.php';
$generator = new LoremIpsum();

// Load progress bar class.
require_once __DIR__.'/libraries/progressbar.php';

// Prompt the user for the number of rows.
$numbers = array();
$types   = array('articles', 'categories', 'users');

print "Specify the number of rows to insert:\r\n";
foreach($types as $type)
{
    while(!isset($numbers[$type]))
    {
        printf("  %s: ", $type);
        $input = trim(fgets(STDIN));

        if(strlen($input) && !is_numeric($input)) {
            print "Error: Value has to be an integer.\r\n";
        } else {
            $numbers[$type] = (int) $input;
        }
    }
}

if(!$numbers['users'] && ($numbers['articles'] || $numbers['categories'])) {
    $numbers['users'] = 1;
}

if(!$numbers['categories'] && $numbers['articles']) {
    $numbers['categories'] = 1;
}

print "\r\n";
unset($types, $type, $input);

// Generate users.
if($numbers['users'])
{
    ProgressBar::start($numbers['users']);
    ProgressBar::setMessage('Adding users...');

    $date_min = new DateTime('-3 years');
    $date_max = new DateTime();
    $autoinc  =  Library\ObjectManager::getInstance()->getObject('com:users.database.table.users')->getSchema()->autoinc;

    for($i = 0; $i < $numbers['users']; $i++)
    {
        $row =  Library\ObjectManager::getInstance()->getObject('com:users.model.users')->getItem();
        $row->name = $generator->words(pick_random(range(1, 3)));
        $row->email = ($autoinc + $i).'@example.'.pick_random(array('com', 'net', 'org'));
        $row->enabled = (int) rand(0, 50) > 2;
        $row->password = $row->email;

        $groups = array_merge(array_fill(0, 100, 18), array(19, 20, 21, 23, 24, 25));
        $row->role_id = pick_random($groups);

        $created = new DateTime('@'.rand($date_min->format('U'), $date_max->format('U')));
        $row->created_on = $created->format('Y-m-d H:i:s');
        $row->created_by = $autoinc + $i;

        $row->save();
        $users[] = $row->id;

        print ProgressBar::next();
    }

    unset($date_min, $date_max, $autoinc, $i, $row, $groups);

    print ProgressBar::finish();
    print "DONE\r\n\r\n";
}

// Generate categories.
if($numbers['categories'])
{
    $categories = array_fill_keys(array('articles'/*, 'contacts'*/), array());

    foreach($categories as $table => $values)
    {
        if(!$numbers[$table]) {
            unset($categories[$table]);
        }
    }

    if($categories)
    {
        ProgressBar::start($numbers['categories'] * count($categories));
        ProgressBar::setMessage('Adding categories...');

        foreach(array_keys($categories) as $table)
        {
            $levels   = array_fill_keys(array(1, 2, 3), array());
            $date_min = new DateTime('-3 years');
            $date_max = new DateTime();

            for($i = 0; $i < $numbers['categories']; $i++)
            {
                $row =  Library\ObjectManager::getInstance()->getObject('com:categories.model.categories')->getItem();
                $row->title = $generator->words(pick_random(range(1, 5)));
                $row->description = $generator->sentences(pick_random(range(0, 3)));
                $row->table = $table;
                $row->published = (int) (rand(0, 20) > 0);

                $created = new DateTime('@'.rand($date_min->format('U'), $date_max->format('U')));
                $row->created_on = $created->format('Y-m-d H:i:s');
                $row->created_by = pick_random($users);

                $level = pick_random(range(1, 3));
                if($level > 1)
                {
                    if($parent_id = pick_random($levels[$level-1])) {
                        $row->parent_id = $parent_id;
                    }
                }

                $row->save();

                $levels[$level + 1][] = $row->id;
                $categories[$table][] = $row->id;

                print ProgressBar::next();
            }
        }

        unset($levels, $date_min, $date_max, $i, $row, $created, $level, $parent_id);

        print ProgressBar::finish();
        print "DONE\r\n\n";
    }
}

// Generate articles.
if($numbers['articles'])
{
    ProgressBar::start($numbers['articles']);
    ProgressBar::setMessage('Adding articles...');

    $date_min = new DateTime('-3 years');
    $date_max = new DateTime();

    for($i = 0; $i < $numbers['articles']; $i++)
    {
        $row =  Library\ObjectManager::getInstance()->getObject('com:articles.model.articles')->getItem();
        $row->title = $generator->words(pick_random(range(1, 5)));
        $row->introtext = $generator->sentences(pick_random(range(1, 3)));
        $row->fulltext = $generator->sentences(pick_random(range(0, 5)));
        $row->published = (int) (rand(0, 20) > 0);
        $row->categories_category_id = pick_random($categories['articles']);

        $created = new DateTime('@'.rand($date_min->format('U'), $date_max->format('U')));
        $row->created_on = $created->format('Y-m-d H:i:s');
        $row->created_by = pick_random($users);

        $row->save();

        print ProgressBar::next();
    }

    unset($date_min, $date_max, $i, $row, $created);

    print ProgressBar::finish();
    print "DONE\r\n\n";
}

exit(0);