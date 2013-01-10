<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

/**
* Koowa Application class
*/
final class JKoowapp extends JApplication
{
    /**
     * Verbose output flag
     */
    protected $_verbose;

    /**
    * Class constructor
    *
    * @param    array An optional associative array of configuration settings.
    * Recognized key values include 'clientId' (this list is not meant to be comprehensive).
    */
    public function __construct($config = array())
    {
        $config['clientId'] = 0;
        parent::__construct($config);

        $this->_verbose = Commandline::getInstance()->get('V');
    }

    /**
    * Dispatch the application
    */
    public function dispatch()
    {
        $cmd = Commandline::getInstance();

        // -v option displays version info
        if($cmd->get('v'))
        {
            include KPATH_HELP.'/version.php';
            die;
        }

        // run all tests or the test specified on the command line
        $this->runTest($cmd->getArg(1));
    }

    /**
     * Run tests
     *
     * @param	string	Test name or false to run all tests
     */
    public function runTest($test = false)
    {
    	 // Create the test suite
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');

        if($test)
        {
            $file = KPATH_TESTS.DS.$test.'.php';
            if(!file_exists($file))
            {
            	die("$file wasn't found.".PHP_EOL);
            }
    	    require_once $file;
            $suite->addTestSuite($test);
        }
        else
        {
        	 // add all tests
            self::_import(KPATH_TESTS, $suite, $this->_verbose);
        }

         // Run test suite
        PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected static function _import($path, $suite, $verbose = false)
    {
        $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($path)
                );

        foreach($files as $file)
        {
            $path = $file->getRealpath();
            $name = $file->getFilename();

            // check if the file or dir is hidden (starting with a dot)
            // if it's a php file, register it
            if(!strpos($path, '/.') AND substr($name, -4) == '.php' )
            {
                $classname = basename($path, '.php');
                if($verbose) echo "Loading class '$classname' in path: $path".PHP_EOL;
                require_once $path;

                $suite->addTestSuite($classname);
            }
        }
    }


}