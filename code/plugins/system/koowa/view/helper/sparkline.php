<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage 	Helper
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Sparkline HTML helper
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  Helper
 */
class KViewHelperSparkline
{
    /**
     * Renders the <img> tag for a sparkline
     *
     * @param	string	Url
     * @param	string	Title
     * @param	array	Parameters (w, h, ...)
     * @return  string	HTML
     */
    public static function img($url, $link = '', $title = '', $params = array())
    {
        $params['format'] = 'sparkline';
        $uri = JURI::getInstance($url);
        foreach($params as $k => $v)
        {
        	$uri->setVar($k, $v);
        }
        $h = isset($params['h']) ? 'height="'.$params['h'].'"' : '';
        $w = isset($params['w']) ? 'width="'.$params['w'].'"' : '';
        $result = '<img src="'.$uri->toString().'" alt="'.$title.'" '.$h.' '.$w.' />';
        if($link)
        {
        	$result = '<a href="'.$link.'" title="'.$title.'">'
                    .$result
                    .'</a>';
        }
        return $result;
    }

}