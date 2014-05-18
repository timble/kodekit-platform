<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Date
 *
 * @author  Gergo Erodsi <http://nooku.assembla.com/profile/gergoerdosis>
 * @package Nooku\Library\Date
 */
class Date extends \DateTime implements DateInterface
{
    /**
     * Constructor.
     *
     * @param   array|ObjectConfig An associative array of configuration settings or a ObjectConfig instance.
     */
    public function __construct($config = array())
    {
        if(!$config instanceof ObjectConfig) {
            $config = new ObjectConfig($config);
        }
        
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
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
        	'date'     => 'now',
            'timezone' => date_default_timezone_get()
        ));
    }

    /**
     * Returns date formatted according to given format.
     *
     * @param  string $format The format to use
     * @return string The formatted data
     */
    public function format($format)
    {
        $format = preg_replace_callback('/(?<!\\\)[DlFM]/', array($this, '_translate'), $format);

        return parent::format($format);
    }

    /**
     * Returns human readable date.
     *
     * @param  string $period The smallest period to use. Default is 'second'.
     * @return string Formatted date.
     */
    public function humanize($period = 'second')
    {
        $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
        $lengths = array(60, 60, 24, 7, 4.35, 12, 10);
        $now     = new \DateTime();

        if($now != $this)
        {
            // TODO: Use DateTime::getTimeStamp().
            if($now > $this)
            {
                $difference = $now->format('U') - $this->format('U');
                $tense      = 'ago';
            }
            else
            {
                $difference = $this->format('U') - $now->format('U');
                $tense      = 'from now';
            }

            for($i = 0; $difference >= $lengths[$i] && $i < 6; $i++) {
                $difference /= $lengths[$i];
            }

            $difference      = round($difference);
            $period_index    = array_search($period, $periods);
            $omitted_periods = $periods;
            array_splice($omitted_periods, $period_index);

            if(in_array($periods[$i], $omitted_periods))
            {
                $difference = 1;
                $i          = $period_index;
            }

            if($periods[$i] == 'day' && ($difference == 1 || $difference == 2))
            {
                if($difference == 1) {
                    $result = \JText::_('Today');
                } else {
                    $result = $tense == 'ago' ? \JText::_('Yesterday') : \JText::_('Tomorrow');
                }
            }
            else
            {
                if($difference != 1) {
                    $periods[$i] .= 's';
                }

                $result = sprintf(\JText::_('%d '.$periods[$i].' '.$tense), $difference);
            }
        }
        else $result = \JText::_('Now');

        return $result;
    }
    
    /**
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as a hash key for storing objects
     * or for identifying an object
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
     * @param array $matches Matched elements of preg_replace_callback.
     * @return string The translated string
     */
    protected function _translate($matches)
    {
        switch ($matches[0]) 
        {
            case 'D':
                $replacement = \JText::_(strtoupper(parent::format('D')));
                break;

            case 'l':
                $replacement = \JText::_(strtoupper(parent::format('l')));
                break;

            case 'F':
                $replacement = \JText::_(strtoupper(parent::format('F')).'_SHORT');
                break;

            case 'M':
                $replacement = \JText::_(strtoupper(parent::format('F')));
                break;
        }

        $replacement = preg_replace('/^([0-9])/', '\\\\\\\\\1', $replacement);
        $replacement = preg_replace('/([a-z])/i', '\\\\\1', $replacement);

        return $replacement;
    }
}