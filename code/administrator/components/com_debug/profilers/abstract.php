<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Abstract Profiler Class
 * 
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
abstract class ComDebugProfilerAbstract extends KObject
{
	/**
     * Start time
     *
     * @var int
     */
    protected $_start = 0;

    /**
     * Prefix used when marking messages
     *
     * @var string
     */
    protected $_prefix = '';

    /**
     * Array of profile marks
     *
     * @var array
     */
    protected $_marks = null;
        
    /**
     * Constructor.
     *
     * @param       object  An optional KConfig object with configuration options
     */
    public function __construct( KConfig $config = null) 
    { 
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
                
        parent::__construct($config);
        
        $this->_start   = $config->start;
        $this->_prefix  = $config->prefix;
        $this->_marks   = KConfig::toData($config->marks);
    }
        
   	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
	 */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'start'   => $this->getTime(),
            'prefix'  => '',
            'marks'   => array()
        ));

       parent::_initialize($config);
    }

	/**
     * Output a time mark
     *
     * @param       string  A label for the time mark
     * @return      string  Formatted mark
     */
     public function mark( $label )
     {
         $mark  = $this->_prefix." $label: ";
         $mark .= sprintf('%.3f', $this->getTime() - $this->_start) . ' seconds';
                
         $mark .= ', '.$this->getMemory();
         
         $this->_marks[] = $mark;  
         return $mark;    
     }

	/**
     * Get the current time.
     *
     * @return float The current time
     */
    public function getTime()
    {
        list( $usec, $sec ) = explode( ' ', microtime() );
        
        return ((float)$usec + (float)$sec);
    }

	/**
     * Get information about current memory usage.
     *
     * @return int The memory usage
     * @link PHP_MANUAL#memory_get_usage
     */
    public function getMemory()
    {
        $size = memory_get_usage(true);
        $unit = array('b','kb','mb','gb','tb','pb');
                
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

	/**
     * Get all profiler marks.
     *
     * Returns an array of all marks created since the Profiler object
     * was instantiated.  Marks are strings as per {@link KProfiler::mark()}.
     *
     * @return array Array of profiler marks
     */
    public function getMarks() 
    {
        return $this->_marks;    
    }
}