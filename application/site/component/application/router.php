<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Router
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationRouter extends Library\DispatcherRouter
{
    public function parse(Library\HttpUrl $url)
	{
		// Get the path
        $path = trim($url->getPath(), '/');

        //Remove base path
        $path = substr_replace($path, '', 0, strlen($this->getObject('request')->getBaseUrl()->getPath()));

        // Set the format
        if(!empty($url->format)) {
            $url->query['format'] = $url->format;
        }

		//Set the route
		$url->path = trim($path , '/');

		return $this->_parseRoute($url);
	}

	public function build(Library\HttpUrl $url)
	{
        $result = $this->_buildRoute($url);

		// Get the path data
		$route = $url->getPath();

        //Add the format to the uri
        if(isset($url->query['format']))
        {
            $format = $url->query['format'];

            if($format != 'html') {
                $url->format = $format;
            }

            unset($url->query['format']);
        }

        //Build the route
        $url->path = $this->getObject('request')->getBaseUrl()->getPath().'/'.$route;
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
        $url->query['site']  = $this->getObject('application')->getSite();

        $route = str_replace($url->query['site'], '', $route);
        $url->path = ltrim($route, '/');

        return true;
    }

    protected function _parsePageRoute($url)
    {
        $route = $url->getPath();
        $pages = $this->getObject('application.pages');

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
                //Get the router identifier
                $identifier = 'com:'.substr($url->query['option'], 4).'.router';

                //Parse the view route
                $query = $this->getObject($identifier)->parse($url);

                //Prevent option and/or itemid from being override by the component router
                $query['option'] = $url->query['option'];
                $query['Itemid'] = $url->query['Itemid'];

                $url->setQuery($query, true);
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
        $identifier = 'com:'.substr($url->query['option'], 4).'.router';

        //Build the view route
        $segments = $this->getObject($identifier)->build($url);

        return $segments;
    }

    protected function _buildPageRoute($url)
    {
        $segments = array();

        //Find the page
        if(!isset($url->query['Itemid']))
        {
            $page = $this->getObject('application.pages')->getActive();
            $url->query['Itemid'] = $page->id;
        }

        $page = $this->getObject('application.pages')->getPage($url->query['Itemid']);

        //Set the page route in the url
        if(!$page->home)
        {
            if($page->getLink()->query['option'] == $url->query['option']) {
                $segments = explode('/', $page->route);
            }
        }

        return $segments;
    }

    protected function _buildSiteRoute($url)
    {
        $segments = array();

        $site = $this->getObject('application')->getSite();
        if($site != 'default' && $site != $this->getObject('application')->getRequest()->getUrl()->toString(Library\HttpUrl::HOST)) {
            $segments[] = $site;
        }

        return $segments;
    }
}
