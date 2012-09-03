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
    public function parse(KHttpUrl $url)
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

            //Get the segments
            $segments = explode('/', $path);
        }
        
	    // Find language if multilanguage is enabled. 
	    $application = JFactory::getApplication();
	    if($application->getCfg('multilanguage'))
	    {
	        $languages = $this->getService('application.languages');
	        
	        // Test if the first segment of the path is a language slug.
	        if(!empty($path) && !empty($segments[0]))
	        {
                foreach($languages as $language)
                {
                    if($segments[0] == $language->slug)
                    {
                        $languages->setActive($language);
                        
                        $vars['language'] = array_shift($segments);
                        $url->setPath(implode($segments, '/'));
                        break;
                    }
                }
	        }
	        
		    // Redirect if language wasn't found.
            if(empty($path) || !isset($vars['language']))
            {
                $redirect  = JURI::base(true).'/'.$languages->getPrimary()->slug;
                $redirect .= '/'.$path.$url->getQuery();
                
                $application->redirect($redirect);
            }
	    }

        if(!empty($path))
        {
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

        $url->query = array_merge($url->query, $vars);
        $url->path  = '';

        return true;
	}

	public function build(KHttpUrl $url)
	{
        $query    = $url->query;
        $segments = array();
        
	    // Add language slug if multilanguage is enabled.
	    $application = JFactory::getApplication();
        if($application->getCfg('multilanguage'))
        {
	        if(!isset($query['language'])) {
	            $segments[] = $application->getLanguages()->getActive()->slug;
	        } else {
	            $segments[] = $query['language'];
	        }
        }
	        
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

        $route = implode('/', $segments);

        //Add the format to the uri
        $format = isset($url->query['format']) ? $url->query['format'] : 'html';

        if($this->getService('application')->getCfg('sef_suffix'))
        {
            $route .= '.'.$format;
            unset($url->query['format']);
        }
        else
        {
            if($format == 'html') {
                unset($url->query['format']);
            }
        }

        //Transform the route
        if($this->getService('application')->getCfg('sef_rewrite')) {
            $route = str_replace('index.php/', '', $route);
        }

        $url->query  = $query;
        $url->path   = KRequest::base()->getPath().'/'.$route;
        $url->format = '';

        return true;
	}
}
