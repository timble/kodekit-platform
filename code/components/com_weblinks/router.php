<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink Router
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */

function WeblinksBuildRoute(&$query)
{
    $segments = array();
    
    if(isset($query['view']) && isset($query['Itemid']))
    {
        $view = JSite::getMenu()->getItem($query['Itemid'])->query['view'];
        
        if($view == 'categories')
        {
            $segments[] = $query['category'];
            unset($query['category']);
            
            if(isset($query['id']))
            {
                $segments[] = $query['id'];
                unset($query['id']);
            }
        }
        
        if($view == 'weblinks') 
        {
            if(isset($query['id']))
            {
                $segments[] = $query['id'];
            
                unset($query['category']);
                unset($query['id']);
            }
        }
        
        if($view == 'weblink') 
        {
            $segments[] = $query['category'];
            unset($query['category']);
            
            $segments[] = $query['id'];
            unset($query['id']);
        }
        
        unset( $query['view'] );
    }
   
    return $segments;
}

function WeblinksParseRoute($segments)
{
	$vars	= array();
	
	$count = count($segments);
	
	if ($count)
	{
	    $count--;
	    $segment = array_shift( $segments );
	    
	    $vars['category'] = str_replace(':', '-', $segment);
	    $vars['view'] = 'weblinks';
	}
	
	if ($count)
	{
	    $count--;
	    $segment = array_shift( $segments) ;
	    
	    $vars['id'] = str_replace(':', '-', $segment);
	    $vars['view'] = 'weblink';
	}
	
	return $vars;
}