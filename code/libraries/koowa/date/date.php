<?php
/**
 * @version		$Id$
 * @package     Koowa_Date
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Date Class
 *
 * @author  	Gergo Erdosi <gergo@timble.net>
 * @package     Koowa_Date
 */
class KDate extends \DateTime implements KDateInterface
{
    /**
     * Constructor.
     *
     * @param   array|KConfig An associative array of configuration settings or a KConfig instance.
     */
    public function __construct($config = array())
    {
        if(!$config instanceof KConfig) $config = new KConfig($config);
        
        $this->_initialize($config);
        
        if (!($config->timezone instanceof \DateTimeZone)) {
            $config->timezone = new \DateTimeZone($config->timezone);
        }
        
        parent::__construct($config->date, $config->timezone);
    }
    
    /**
     * Initializes the options for the date object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'date'     => 'now',
            'timezone' => date_default_timezone_get()
        ));
    }

    /**
     * Returns date formatted according to given format.
     *
     * @param  string The format to use
     * @return string The formatted data
     */
    public function format($format)
    {
        $format = preg_replace_callback('/(?<!\\\)[DlFM]/', array($this, '_translate'), $format);

        return parent::format($format);
    }
    
    /**
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as
     * a hash key for storing objects or for identifying an object
     *
     * @return string A string that is unique
     */
    public function getHandle()
    {
        return spl_object_hash($this);
    }

    /**
     * Translates day and month names.
     *
     * @param array Matched elements of preg_replace_callback.
     * @return The translated string
     */
    protected function _translate($matches)
    {
        switch ($matches[0]) 
        {
            case 'D':
                $replacement = JText::_(strtoupper(parent::format('D')));
                break;

            case 'l':
                $replacement = JText::_(strtoupper(parent::format('l')));
                break;

            case 'F':
                $replacement = JText::_(strtoupper(parent::format('F')).'_SHORT');
                break;

            case 'M':
                $replacement = JText::_(strtoupper(parent::format('F')));
                break;
        }

        $replacement = preg_replace('/^([0-9])/', '\\\\\\\\\1', $replacement);
        $replacement = preg_replace('/([a-z])/i', '\\\\\1', $replacement);

        return $replacement;
    }
}