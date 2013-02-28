<?php
/**
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
class ComApplicationRouter extends KDispatcherRouter
{
    public function parse(KHttpUrl $url)
	{
        $vars = array();
        $path = trim($url->getPath(), '/');

        //Remove base path
        $path = substr_replace($path, '', 0, strlen($this->getService('request')->getBaseUrl()->getPath()));

        // Set the format
        if(!empty($url->format)) {
            $url->query['format'] = $url->format;
        }

        //Parse site route
        $url->query['site'] = $this->getService('application')->getSite();

        $path = str_replace($url->query['site'], '', $path);
        $path = ltrim($path, '/');

        //Parse component route
        if(!empty($path))
        {
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

        $url->query = array_merge($url->query, $vars);
        $url->path  = '';

        return true;
	}

	public function build(KHttpUrl $url)
	{
        $query    = $url->query;
        $segments = array();

        //Build site route
        $site = $this->getService('application')->getSite();
        if($site != 'default' && $site != $this->getService('application')->getRequest()->getUrl()->toString(KHttpUrl::HOST)) {
            $segments[] = $site;
        }

	    //Build commponent route
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

        $url->query  = $query;

        //Build the route
        $route  = implode('/', $segments);

        //Add the format to the uri
        $format = isset($url->query['format']) ? $url->query['format'] : 'html';

        if($this->getService('application')->getCfg('sef_suffix'))
        {
            $url->format = $format;
            unset($url->query['format']);
        }
        else
        {
            $url->format = '';
            if($format == 'html') {
                unset($url->query['format']);
            }
        }

        $url->path = $this->getService('request')->getBaseUrl()->getPath().'/'.$route;

        // Removed unused query variables
        unset($url->query['Itemid']);
        unset($url->query['option']);

        return true;
	}
}
