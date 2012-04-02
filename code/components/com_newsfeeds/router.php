<?php
/**
 * @version		$Id: router.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Newsfeed Router
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */

function NewsfeedsBuildRoute(&$query)
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
        
        if($view == 'newsfeeds') 
        {
            if(isset($query['id']))
            {
                $segments[] = $query['id'];
            
                unset($query['category']);
                unset($query['id']);
            }
        }
        
        if($view == 'newsfeed') 
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

function NewsfeedsParseRoute($segments)
{
	$vars	= array();
	
	$view = JSite::getMenu()->getActive()->query['view'];
	
	if($view == 'categories')
	{
	    $count = count($segments);
	    
	    if ($count)
	    {
	        $count--;
	        $segment = array_shift( $segments );
	    
	        $vars['category'] = str_replace(':', '-', $segment);
	        $vars['view'] = 'newsfeeds';
	    }
	
	    if ($count)
	    {
	        $count--;
	        $segment = array_shift( $segments) ;
	    
	        $vars['id'] = str_replace(':', '-', $segment);
	        $vars['view'] = 'newsfeed';
	    }
	}
	
	if($view == 'newsfeeds') 
	{
	    $segment = array_shift( $segments) ;
	     
	    $vars['id'] = str_replace(':', '-', $segment);
	    $vars['view'] = 'newsfeed';
	}
	
	return $vars;
}