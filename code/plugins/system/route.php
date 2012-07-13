<?php
/**
 * @version     $Id: default.php 2776 2011-01-01 17:08:00Z johanjanssens $
 * @package     Nooku_Plugins
 * @subpackage  Koowa
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Route System Plugin
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Plugins
 * @subpackage  System
 */
class plgSystemRoute extends PlgKoowaDefault
{
	/**
     * Converting the site URL to fit to the HTTP request
     */
	public function onAfterControllerRender(KEvent $event)
	{
		//Replace src links
      	$base   = JURI::base(true).'/';
		$buffer = JResponse::getBody();
		
		// do the SEF substitutions
       	$regex  = '#(href|src|action|location.href|<option\s+value)(="|=\')(index.php[^"]*)#m';
      	$buffer = preg_replace_callback( $regex, array($this, 'route'), $buffer );

       	$protocols = '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
      	$regex     = '#(src|href)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
        $buffer    = preg_replace($regex, "$1=\"$base\$2\"", $buffer);
		$regex     = '#(onclick="window.open\(\')(?!/|'.$protocols.'|\#)([^/]+[^\']*?\')#m';
		$buffer    = preg_replace($regex, '$1'.$base.'$2', $buffer);
		
		// ONMOUSEOVER / ONMOUSEOUT
		$regex 		= '#(onmouseover|onmouseout)="this.src=([\']+)(?!/|'.$protocols.'|\#|\')([^"]+)"#m';
		$buffer 	= preg_replace($regex, '$1="this.src=$2'. $base .'$3$4"', $buffer);
		
		// Background image
		$regex 		= '#style\s*=\s*[\'\"](.*):\s*url\s*\([\'\"]?(?!/|'.$protocols.'|\#)([^\)\'\"]+)[\'\"]?\)#m';
		$buffer 	= preg_replace($regex, 'style="$1: url(\''. $base .'$2$3\')', $buffer);
		
		// OBJECT <param name="xx", value="yy"> -- fix it only inside the <param> tag
		$regex 		= '#(<param\s+)name\s*=\s*"(movie|src|url)"[^>]\s*value\s*=\s*"(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
		$buffer 	= preg_replace($regex, '$1name="$2" value="' . $base . '$3"', $buffer);
		
		// OBJECT <param value="xx", name="yy"> -- fix it only inside the <param> tag
		$regex 		= '#(<param\s+[^>]*)value\s*=\s*"(?!/|'.$protocols.'|\#|\')([^"]*)"\s*name\s*=\s*"(movie|src|url)"#m';
		$buffer 	= preg_replace($regex, '<param value="'. $base .'$2" name="$3"', $buffer);

		// OBJECT data="xx" attribute -- fix it only in the object tag
		$regex = 	'#(<object\s+[^>]*)data\s*=\s*"(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
		$buffer 	= preg_replace($regex, '$1data="' . $base . '$2"$3', $buffer);
		
		JResponse::setBody($buffer);
		return true;
	}

	/**
     * Replaces the matched tags
     *
     * @param array An array of matches (see preg_match_all)
     * @return string
     */
   	 public function route( &$matches )
     {    
         $url = str_replace('&amp;','&',$matches[3]);
         $uri = new JURI(JURI::base(true).'/'.$url);
        
         //Remove basepath
		 $path = substr_replace($uri->getPath(), '', 0, strlen(JURI::base(true)));
		  
		 //Remove prefix
		 $path = trim(str_replace('index.php', '', $path), '/');
		   
         if(empty($path)) 
         {
             $route  = JRoute::_($url);
             $result =  $matches[1].$matches[2].$route;
         }
         else $result = $matches[0];
         
         return $result;
      }
}
