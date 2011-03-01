<?php
/**
 * @version		$Id: default.php 2057 2010-05-15 20:48:00Z johanjanssens $
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Template Helper Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @uses   		KFactory
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
			'date'   	 => '',
			'format'	 => '%A, %d %B %Y',
			'gmt_offset' => 0,
 		));

 		if(!is_numeric($config->date)) {
 			$config->date =  strtotime($config->date);
 		}

 		return strftime($config->format, $config->date + 3600 * $config->gmt_offset);
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
            'date'              => null,
            'gmt_offset'        => 0,
            'smallest_period'   => 'day'
        ));

        $periods    = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
        $lengths    = array(60, 60, 24, 7, 4.35, 12, 10);
        $now        = gmmktime();
        $time       = is_numeric($config->date) ? $config->date : strtotime($config->date);

        if($config->gmt_offset != 0) {
            $time =  $time + 3600 * $config->gmt_offset;
        }

        if($now > $time)
        {
            $difference = $now - $time;
            $tense      = 'ago';
        }
        else
        {
            $difference = $time;
            $tense      = 'from now';
        }

        for($i = 0; $difference >= $lengths[$i] && $i < 6; $i++) {
            $difference /= $lengths[$i];
        }

        $difference         = round($difference);
        $period_index       = array_search($config->smallest_period, $periods);
        $omitted_periods    = $periods;
        array_splice($omitted_periods, $period_index);

        if(in_array($periods[$i], $omitted_periods))
        {
            $difference = 1;
            $i          = $period_index;
        }

        if($periods[$i] == 'day')
        {
            switch($difference)
            {
                case 1:
                    return 'Today';
                    break;

                case 2:
                    return $tense == 'ago' ? 'Yesterday' : 'Tomorrow';
                    break;
            }
        }

        if($difference != 1) {
            $periods[$i].= 's';
        }

        return sprintf(JText::_('%s '.$periods[$i].' '.$tense), $difference);
    }
}