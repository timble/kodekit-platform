<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activities JSON View Class
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 * @see 	http://activitystrea.ms/specs/json/1.0/
 */
class ViewActivitiesJson extends Library\ViewJson
{
	/**
	 * Get the list data
	 *
	 * @return array 	The array with data to be encoded to json
	 */
	protected function _getRowset()
	{
		//Get the model
	    $model = $this->getModel();

	    //Get the route
		$route = $this->getRoute();

		//Get the model state
		$state = $model->getState();

        //Get the model paginator
        $paginator = $model->getPaginator();

	    $vars = array();
	    foreach($state->toArray() as $var)
	    {
	        if(!$var->unique) {
	            $vars[] = $var->name;
	        }
	    }

		$data = array(
			'version'  => '1.0',
			'href'     => (string) $route->setQuery($state->getValues()),
			'url'      => array(
				'type'     => 'application/json',
				'template' => (string) $route->get(Library\HttpUrl::BASE).'?{&'.implode(',', $vars).'}',
			),
			'offset'   => (int) $paginator->offset,
			'limit'    => (int) $paginator->limit,
			'total'	   => 0,
			'items'    => array(),
			'queries'  => array()
		);

		if($list = $model->getRowset())
		{
		    $vars = array();
	        foreach($state->toArray() as $var)
	        {
	            if($var->unique)
	            {
	                $vars[] = $var->name;
	                $vars   = array_merge($vars, $var->required);
	            }
	        }

		    $items = array();
			foreach($list as $item)
			{
			    $id = array(
			    	'tag:'.$this->getObject('request')->getUrl()->toString(Library\HttpUrl::BASE),
			    	'id:'.$item->id
				);

			    $items[] = array(
			    	'id' => implode(',', $id),
			    	'published' => $this->getObject('com:activities.template.helper.date')->format(array(
			    		'date'   => $item->created_on,
			    		'format' => '%Y-%m-%dT%TZ'
				    )),
		    		'verb' => $item->action,
	        		'object' => array(
	        			'url' => $this->getRoute('option=com_'.$item->package.'&view='.$item->name.'&id='.$item->row),
	                ),
			    	'target' => array(
			    		'url' => $this->getRoute('option=com_'.$item->package.'&view='.$item->name),
				    ),
				    'actor' => array(
				    	'url' => $this->getRoute('option=com_users&view=user&id='.$item->created_by),
					)
			    );
			}

			$queries = array();
            foreach(array('first', 'prev', 'next', 'last') as $offset)
            {
                $page = $paginator->pages->{$offset};
                if($page->active)
                {
                    $queries[] = array(
		   				'rel' => $page->rel,
		   				'href' => (string) $this->getRoute('limit='.$page->limit.'&offset='.$page->offset)
                    );
                }
            }

            $data = array_merge($data, array(
				'total'    => $paginator->total,
				'items'    => $items,
		        'queries'  => $queries
			 ));
		}

		return $data;
	}
}
