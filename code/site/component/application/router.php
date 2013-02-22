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
		// Get the path
        $path = trim($url->getPath(), '/');

        //Remove base path
        $path = substr_replace($path, '', 0, strlen($this->getService('request')->getBasePath()));

        // Set the format
        if(!empty($url->format)) {
            $url->query['format'] = $url->format;
        }

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

        $url->path   = $this->getService('request')->getBasePath().'/'.$route;
		return $result;
	}

	protected function _parseRoute($url)
	{
        $this->_parseSiteRoute($url);
        $this->_parsePageRoute($url);
        $this->_parseComponentRoute($url);

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
        $pages = $this->getService('application.pages');

        //Find the page
        if(!empty($route))
        {
            //Need to reverse the array (highest sublevels first)
            foreach(array_reverse($pages->id) as $id)
            {
                $page   = $pages->getPage($id);
                $length = strlen($page->route);

                if($length > 0 && strpos($route.'/', $page->route.'/') === 0 && $page->type != 'pagelink')
                {
                    $route     = substr($route, $length);
                    $url->path =  ltrim($route, '/');
                    break;
                }
            }
        }
        else $page = $pages->getHome();

        //Set the page information in the route
        if($page->type != 'redirect')
        {
            $url->setQuery($page->getLink()->query, true);
            $url->query['Itemid'] = $page->id;
        }

        $pages->setActive($page->id);

        return true;
    }

    protected function _parseComponentRoute($url)
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
                $vars = $this->getService($identifier)->parseRoute($route);

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

        $view = $this->_buildComponentRoute($url);
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

    protected function _buildComponentRoute($url)
    {
        $segments = array();

        //Get the router identifier
        $identifier = 'com://site/'.substr($url->query['option'], 4).'.router';

        //Build the view route
        $segments = $this->getService($identifier)->buildRoute($url->query);

        return $segments;
    }

    protected function _buildPageRoute($url)
    {
        $segments = array();

        //Find the page
        if(!isset($url->query['Itemid']))
        {
            $page = $this->getService('application.pages')->getActive();
            $url->query['Itemid'] = $page->id;
        }

        $page = $this->getService('application.pages')->getPage($url->query['Itemid']);

        //Set the page route in the url
        if(!$page->home)
        {
            if($page->getLink()->query['option'] == $url->query['option']) {
                $segments = $page->route;
            }

            $segments = explode('/', $segments);
        }

        return $segments;
    }

    protected function _buildSiteRoute($url)
    {
        $segments = array();

        $site = $this->getService('application')->getSite();
        if($site != 'default' && $site != $this->getService('application')->getRequest()->getUrl()->toString(KHttpUrl::HOST)) {
            $segments[] = $site;
        }

        return $segments;
    }
}
