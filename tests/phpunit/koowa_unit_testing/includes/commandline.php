<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Class to deal with command line arguments
 */
class Commandline
{
    /**
     * List of parsed options
     * @link	http://www.php.net/getopt
     */
    const OPTIONS = 'hvV';

    /**
     * Parsed options
     */
    protected $_opts = array();

    /**
     * Arguments
     */
    protected $_args = array();

    /**
     * Constructor
     */
    protected function __construct()
    {
    	$this->_opts = getopt(self::OPTIONS);

        foreach($_SERVER['argv'] as $k => $arg)
        {
        	if(substr($arg, 0, 1)=='-')
            {
            	unset($_SERVER['argv'][$k]);
            }
        }
        $this->_args = array_values(array_filter($_SERVER['argv']));
    }

    /**
     * Creates a singleton Commandline object
     *
     * @return Commandline
     */
    public static function getInstance()
    {
    	static $instance;
        if(!isset($instance))
        {
        	$instance = new Commandline;
        }
        return $instance;
    }

    /**
     * Get option
     *
     * @param	string			Option letter
     * @return 	boolean|string False when the options wasn't set in the
     * commandline, true when it was set, and string when a value was provided
     */
    public function get($opt)
    {
    	if(isset($this->_opts[$opt]))
        {
        	return $this->_opts[$opt] ? $this->_opts[$opt] : true;
        }
        return false;
    }

    /**
     * Get Argument(s)
     *
     * @param	integer		Argument key
     * @return  string      Argument value
     */
    public function getArg($key)
    {
        if(isset($this->_args[$key]))
        {
    	   return $this->_args[$key];
        }
        return false;
    }
}