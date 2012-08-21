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
		// Get the application
		$app = $this->getService('application');

		// Get the path
		$path = $url->getPath();

		if($app->getCfg('sef_suffix') && !(substr($path, -9) == 'index.php' || substr($path, -1) == '/'))
		{
			if($format = pathinfo($path, PATHINFO_EXTENSION))
			{
				$path = str_replace('.'.$format, '', $path);
				$vars['format'] = $format;
			}
		}

		//Remove base path
		$path = substr_replace($path, '', 0, strlen(KRequest::root()->getPath()));

		//Remove the filename
		$path = str_replace('index.php', '', $path);

		//Set the route
		$url->path = trim($path , '/');

		return parent::parse($url);
	}

	public function build(KHttpUrl $url)
	{
        $result = parent::build($url);

		// Get the path data
		$route = $url->getPath();

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

		$url->path   = KRequest::root()->getPath().'/'.$route;
        $url->format = '';

		return $result;
	}

	protected function _parseRoute($url)
	{
        $this->_parseSiteRoute($url);
        $this->_parsePageRoute($url);
        $this->_parseViewRoute($url);

		return true;
	}

    protected function _parseSiteRoute($url)
    {
        $route = $url->getPath();

        //Find the site
        $url->query['site']  = $this->getService('application')->getSite();

        $route = str_replace($url->query['site'], '', $route);
        $url->path = ltrim($route, '/');

        return true;
    }

    protected function _parsePageRoute($url)
    {
        $route = $url->getPath();
        $pages = $this->getService('application')->getPages();

        if(substr($route, 0, 9) != 'component')
        {
            //Need to reverse the array (highest sublevels first)
            foreach(array_reverse($pages->id) as $id)
            {
                $page   = $pages->find($id);
                $length = strlen($page->route);

                if($length > 0 && strpos($route.'/', $page->route.'/') === 0 && $page->type != 'pagelink')
                {
                    $route = substr($route, $length);

                    if($page->type != 'redirect')
                    {
                        $url->query = $page->link->query;
                        $url->query['Itemid'] = $page->id;
                    }

                    $pages->setActive($page->id);
                    break;
                }
            }
        }
        else
        {
            $segments = explode('/', $route);
            $route    = str_replace('component/'.$segments[1], '', $route);

            $url->query['Itemid'] = $pages->getHome()->id;
            $url->query['option'] = 'com_'.$segments[1];
        }

        $url->path =  ltrim($route, '/');

        return true;
    }

    protected function _parseViewRoute($url)
    {
        $route = $url->path;

        if(isset($url->query['option']) )
        {
            if(!empty($route))
            {
                //Store the default
                $defaults = array(
                    'option' => $url->query['option'],
                    'Itemid' => $url->query['Itemid']
                );

                //Get the router identifier
                $identifier = 'com://site/'.substr($url->query['option'], 4).'.router';

                //Parse the view route
                $vars = KService::get($identifier)->parseRoute($route);

                //Merge default and vars
                $url->query = array_merge($defaults, $vars);
            }
        }

        $url->path = '';

        return true;
    }

	protected function _buildRoute($url)
	{
        $segments = array();

        $view = $this->_buildViewRoute($url);
        $page = $this->_buildPageRoute($url);
        $site = $this->_buildSiteRoute($url);

        $segments = array_merge($site, $page, $view);

        //Set the path
        $url->path = array_filter($segments);

        // Removed unused query variables
        unset($url->query['Itemid']);
        unset($url->query['option']);

        return true;
	}

    protected function _buildViewRoute($url)
    {
        $segments = array();

        // Use the custom routing handler if it exists
        if (isset($url->query['option']))
        {
            //Get the router identifier
            $identifier = 'com://site/'.substr($url->query['option'], 4).'.router';

            //Build the view route
            $segments = KService::get($identifier)->buildRoute($url->query);
        }

        return $segments;
    }

    protected function _buildPageRoute($url)
    {
        $segments = '';

        if(!isset($url->query['Itemid']))
        {
            $page = $this->getService('application')->getPages()->getActive();
            if($page) {
                $url->query['Itemid'] = $page->id;
            }
        }

        if(isset($url->query['Itemid']))
        {
            $pages = $this->getService('application')->getPages();
            $page  = $pages->find($url->query['Itemid']);

            if($page->link->query['option'] == $url->query['option']) {
                $segments = $page->route;
            } else {
                $segments = 'component/'.substr($url->query['option'], 4);
            }
        }
        else $segments = 'component/'.substr($url->query['option'], 4);

        $segments = explode('/', $segments);

        return $segments;
    }

    protected function _buildSiteRoute($url)
    {
        $segments = array();

        $site = $this->getService('application')->getSite();
        if($site != 'default' && $site != KRequest::url()->getUrl(KHttpUrl::HOST)) {
            $segments[] = $site;
        }

        return $segments;
    }
}
