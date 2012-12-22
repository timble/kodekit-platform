#!/usr/bin/env php
<?php
/**
 * @package     Nooku_Server
 * @subpackage  Sample_Data
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sample data generator
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Sample_Data
 */

// Exit if script is not called from command line.
if(PHP_SAPI != 'cli')
{
    echo 'This script is only meant to run at the command line.';
    exit(1);
}

set_time_limit(0);

function pick_random(array $array)
{
    $key = array_rand($array);
    return !is_null($key) ? $array[$key] : null;
}

// Load Koowa libraries.
define('_JEXEC', 1);
define('JPATH_APPLICATION', realpath(__DIR__.'/../../code/administrator'));
define('JPATH_BASE'       , JPATH_APPLICATION);
define('JPATH_ROOT'       , dirname(JPATH_APPLICATION));
define('JPATH_LIBRARIES'  , JPATH_ROOT.'/libraries' );
define('JPATH_SITES'      , JPATH_ROOT.'/sites');
define('DS', DIRECTORY_SEPARATOR);

require_once(JPATH_APPLICATION.'/bootstrap.php');

KLoader::loadIdentifier('com://admin/application.aliases');
KService::get('com://admin/application.dispatcher')->loadConfig(new KCommandContext());

// Load Lorem Ipsum generator class.
require_once __DIR__.'/libraries/loremipsum.php';
$generator = new LoremIpsum();

// Load progress bar class.
require_once __DIR__.'/libraries/progressbar.php';

// Define row numbers.
$numbers = array(
    'articles'   => 100000,
    'categories' => 1000,
    'users'      => 1000
);

// Generate users.
ProgressBar::start($numbers['users']);
ProgressBar::setMessage('Adding users...');

for($i = 0; $i < $numbers['users']; $i++)
{
    $row = KService::get('com://admin/users.model.users')->getItem();
    $row->name = $generator->words(pick_random(range(1, 3)));
    $row->email = $i.'@example.'.pick_random(array('com', 'net', 'org'));
    $row->enabled = (int) rand(0, 50) > 2;
    $row->password = (string) rand(100000, 1000000);

    $groups = array_merge(array_fill(0, 100, 18), array(19, 20, 21, 23, 24, 25));
    $row->role_id = pick_random($groups);

    $row->save();

    echo ProgressBar::next();
}
echo ProgressBar::finish();
echo "DONE\r\n\r\n";

// Generate categories.
$categories = array('articles' => array(), 'contacts' => array(), 'weblinks' => array());

ProgressBar::start($numbers['categories'] * count($categories));
ProgressBar::setMessage('Adding categories...');

foreach(array_keys($categories) as $table)
{
    $levels = array(1 => array(), 2 => array());

    for($i = 0; $i < $numbers['categories']; $i++)
    {
        $row = KService::get('com://admin/categories.model.categories')->getItem();
        $row->title = $generator->words(pick_random(range(1, 5)));
        $row->description = $generator->sentences(pick_random(range(0, 3)));
        $row->table = $table;
        $row->published = (int) (rand(0, 20) > 0);

        $level = pick_random(range(1, 3));
        if($level > 1)
        {
            $parent_id = pick_random($levels[$level-1]);
            $row->parent_id = (int) $parent_id;
        }

        $row->save();

        $levels[$level + 1][] = $row->id;
        $categories[$table][] = $row->id;

        echo ProgressBar::next();
    }
}

unset($levels);

echo ProgressBar::finish();
echo "DONE\r\n\r\n";

// Generate articles.
ProgressBar::start($numbers['articles']);
ProgressBar::setMessage('Adding articles...');

for($i = 0; $i < $numbers['articles']; $i++)
{
    $row = KService::get('com://admin/articles.model.articles')->getItem();
    $row->title = $generator->words(pick_random(range(1, 5)));
    $row->introtext = $generator->sentences(pick_random(range(1, 3)));
    $row->fulltext = $generator->sentences(pick_random(range(0, 5)));
    $row->published = (int) (rand(0, 20) > 0);
    $row->featured = pick_random(array_merge(array_fill(0, 5, 0), array(1)));
    $row->categories_category_id = pick_random($categories['articles']);

    $row->save();

    echo ProgressBar::next();
}
echo ProgressBar::finish();
echo "DONE\r\n\r\n";

exit(0);