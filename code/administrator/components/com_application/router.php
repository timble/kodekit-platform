<?php
/**
 * @version     $Id: dispatcher.php 4021 2012-07-13 01:00:23Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Router Class
.*
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationRouter extends KDispatcherRouterDefault
{
    public function parse($url)
	{
        $vars = array();

        $path = trim($url->getPath(), '/');

        //Remove basepath
        $path = substr_replace($path, '', 0, strlen(JURI::base(true)));

        //Remove prefix
        $path = trim(str_replace('index.php', '', $path), '/');

        //Remove suffix
        if(!empty($path))
        {
            if($suffix = pathinfo($path, PATHINFO_EXTENSION))
            {
                $path = str_replace('.'.$suffix, '', $path);
                $vars['format'] = $suffix;
            }
        }

        if(!empty($path))
        {
            //Get the segments
            $segments = explode('/', $path);
            if(isset($segments[0]))
            {
                $vars['option'] = 'com_'.$segments[0];

                if(isset($segments[1])) {
                    $vars['view']   = $segments[1];
                } else {
                    $vars['view']   = $segments[0];
                }
            }
        }

        return $vars;
	}

	public function build($url)
	{
        //Create the url object
        $url = $this->_createUrl($url);

        $query = $url->getQuery(true);
        $path  = $url->getPath();

        $segments = array();
        if(isset($query['option']))
        {
            $segments[] = substr($query['option'], 4);
            unset($query['option']);

            if(isset($query['view']))
            {
                if($query['view'] != $segments[0]) {
                    $segments[] = $query['view'];
                }

                unset($query['view']);
            }
        }

        $path = implode('/', $segments);

        //Add format to path
        if($format = $url->getVar('format', 'html'))
        {
            if(JFactory::getApplication()->getCfg('sef_suffix') && !empty($path))
            {
                $path .= '.'.$format;
                unset($query['format']);
            }
            else
            {
                if($format == 'html') {
                    unset($query['format']);
                }
            }
        }

        //Add index.php to the path
        if(!JFactory::getApplication()->getCfg('sef_rewrite')) {
            $path = 'index.php/'.$path;
        }

        //Set query again in the URI
        $url->setQuery($query);
        $url->setPath(JURI::base(true).'/'.$path);

        return $url;
	}
}
