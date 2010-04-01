<?php 
/**
 * @version		$Id$
 * @package		Koowa
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */
 
 /**
 * Date helper, gives you things like facebook style '2 hours ago'
 *
 * @author		Stian Didriksen <stian@ninjaforge.com>
 */
class ComProfilesHelperDate extends KObject
{

	/**
	 * Provieds human readable datetime presentations
	 *
	 * Time past example: 2 days ago
	 *
	 * Time to example: 35 seconds from now
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	string $date
	 * @return	string
	 */
	public function humanize($date)
	{
	    if(empty($date)) return JText::_('No date provided');
	    
	    $periods         = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
	    $lengths         = array(60, 60, 24, 7, 4.35, 12, 10);
	    
	    $now             = time();
	    $unix_date       = strtotime($date);
	    
		// check validity of date
	    if(empty($unix_date)) return;
	
	    // is it future date or past date
	    if($now > $unix_date)
	    {    
	        $difference	= $now - $unix_date;
	        $tense		= 'ago';
	    }
	    else
	    {
	        $difference	= $unix_date - $now;
	        $tense		= 'from now';
	    }
	    
	    for($i = 0; $difference >= $lengths[$i] && $i < 6; $i++)
	    {
	        $difference /= $lengths[$i];
	    }
	    $difference = round($difference);
	    
	    if($difference != 1) $periods[$i].= 's';

		return sprintf(JText::_('%s '.$periods[$i].' '.$tense), $difference);
	}
}