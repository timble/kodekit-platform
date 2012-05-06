<?php
/**
 * @version     $Id: default.php 2057 2010-05-15 20:48:00Z johanjanssens $
 * @package     Koowa_Template
 * @subpackage  Helper
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Helper Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Helper
 */
class KTemplateHelperDate extends KTemplateHelperAbstract
{
    /**
     * Returns formatted date according to current local and adds time offset.
     *
     * @param  array   An optional array with configuration options.
     * @return string  Formatted date.
     * @see    strftime
     */
    public function format($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'date'     => 'now',
            'timezone' => date_default_timezone_get(),
            'format'   => JText::_('DATE_FORMAT_LC'),
            'default'  => ''
        ));

        $return = $config->default;

        if (!in_array($config->date, array('0000-00-00 00:00:00', '0000-00-00'))) 
        {
            try 
            {
                $date = new KDate(array('date' => $config->date, 'timezone' => 'UTC'));
                $date->setTimezone(new DateTimeZone($config->timezone));

                $return = $date->format($config->format);
            } 
            catch (Exception $e) {}
        }

        return $return;
    }

    /**
     * Returns human readable date.
     *
     * @param  array   An optional array with configuration options.
     * @return string  Formatted date.
     */
    public function humanize($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'date'            => 'now',
            'timezone'        => date_default_timezone_get(),
            'default'         => JText::_('Never'),
            'smallest_period' => 'second'
        ));

        $result = $config->default;

        if (!in_array($config->date, array('0000-00-00 00:00:00', '0000-00-00'))) 
        {
            $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
            $lengths = array(60, 60, 24, 7, 4.35, 12, 10);
            $now     = new DateTime();

            try 
            {
                $date = new KDate(array('date' => $config->date, 'timezone' => 'UTC'));
                $date->setTimezone(new DateTimeZone($config->timezone));

                if ($now != $date) 
                {
                    // TODO: Use DateTime::getTimeStamp().
                    if ($now > $date) 
                    {   
                        $difference = $now->format('U') - $date->format('U');
                        $tense      = 'ago';
                    } 
                    else 
                    {
                        $difference = $date->format('U') - $now->format('U');
                        $tense      = 'from now';
                    }

                    for ($i = 0; $difference >= $lengths[$i] && $i < 6; $i++) {
                        $difference /= $lengths[$i];
                    }

                    $difference      = round($difference);
                    $period_index    = array_search($config->smallest_period, $periods);
                    $omitted_periods = $periods;
                    array_splice($omitted_periods, $period_index);

                    if (in_array($periods[$i], $omitted_periods)) 
                    {
                        $difference = 1;
                        $i          = $period_index;
                    }

                    if ($periods[$i] == 'day' && ($difference == 1 || $difference == 2)) 
                    {
                        if ($difference == 1) {
                            $result = JText::_('Today');
                        } else {
                            $result = $tense == 'ago' ? JText::_('Yesterday') : JText::_('Tomorrow');
                        }
                    } 
                    else 
                    {
                        if ($difference != 1) {
                            $periods[$i] .= 's';
                        }

                        $result = sprintf(JText::_('%s '.$periods[$i].' '.$tense), $difference);
                    }
                } 
                else $result = JText::_('Now');
            } 
            catch (Exception $e) {}
        }

        return $result;
    }
}