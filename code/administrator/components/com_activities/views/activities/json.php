<?php
/**
 * @version		$Id: json.php 1485 2012-02-10 12:32:02Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Activities JSON View Class
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Activities
 * @see 		http://activitystrea.ms/specs/json/1.0/
 */

class ComActivitiesViewActivitiesJson extends KViewJson
{
	/**
	 * Get the list data
	 *
	 * @return array 	The array with data to be encoded to json
	 */
	protected function _getList()
	{
		//Get the model
	    $model = $this->getModel();

	    //Get the route
		$route = $this->getRoute();

		//Get the model state
		$state = $model->getState();

		//Get the paginator
		$paginator = new KConfigPaginator(array(
          	'offset' => (int) $model->offset,
           	'limit'  => (int) $model->limit,
		    'total'  => (int) $model->getTotal(),
        ));

	    $vars = array();
	    foreach($state->toArray(false) as $var)
	    {
	        if(!$var->unique) {
	            $vars[] = $var->name;
	        }
	    }

		$data = array(
			'version'  => '1.0',
			'href'     => (string) $route->setQuery($state->toArray()),
			'url'      => array(
				'type'     => 'application/json',
				'template' => (string) $route->get(KHttpUrl::BASE).'?{&'.implode(',', $vars).'}',
			),
			'offset'   => (int) $paginator->offset,
			'limit'    => (int) $paginator->limit,
			'total'	   => 0,
			'items'    => array(),
			'queries'  => array()
		);

		if($list = $model->getList())
		{
		    $vars = array();
	        foreach($state->toArray(false) as $var)
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
			    	'tag:'.KRequest::get('server.HTTP_HOST', 'string'),
			    	'id:'.$item->id
				);

			    $items[] = array(
			    	'id' => implode(',', $id),
			    	'published' => $this->getService('com://admin/activities.template.helper.date')->format(array(
			    		'date'   => $item->created_on,
			    		'format' => '%Y-%m-%dT%TZ'
				    )),
		    		'verb' => $item->action,
	        		'object' => array(
	        			'url' => JRoute::_($this->getService('koowa:http.url', array('url' => 'index.php'))->setQuery(array(
				    		'option' => $item->type.'_'.$item->package,
				    		'view'   => $item->name,
				    		'id'     => $item->row,
				    	))),
	                ),
			    	'target' => array(
			    		'url' => JRoute::_($this->getService('koowa:http.url', array('url' => 'index.php'))->setQuery(array(
				    		'option' => $item->type.'_'.$item->package,
				    		'view'   => $item->name,
				    	))),
				    ),
				    'actor' => array(
				    	'url' => JRoute::_($this->getService('koowa:http.url', array('url' => 'index.php'))->setQuery(array(
				    		'option' => 'com_users',
				    		'view'   => 'user',
				    		'id'     => $item->created_by
				    	))),
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
