<?php
/**
 * @version     $Id: default.php 1982 2010-05-09 00:21:45Z johanjanssens $
 * @category    Koowa
 * @package     Koowa_Template
 * @subpackage  Helper
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Helper Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Template
 * @subpackage  Helper
 */
class KTemplateHelper 
{
    /**
     * Factory method for KTemplateHelperInterface classes.
     *
     * @param   mixed 	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
     * @param   object  An optional KConfig object with configuration options
     * @return KTemplateHelperAbstract
     */
    public static function factory($helper, $config = array())
    {       
        //Create the template helper
	    if(!($helper instanceof KTemplateHelperInterface))
		{   
		    $identifier = $helper;
		    if(is_string($identifier) && strpos($identifier, ':') === false ) {
		       $identifier = 'com:default.template.helper.'.trim($identifier);
		    }    
		   
		    $helper = KFactory::get($identifier, $config);
		    
		    //Check the behavior interface
            if(!($helper instanceof KTemplateHelperInterface)) {
                throw new KTemplateHelperException("Template helper $identifier does not implement KTemplateHelperInterface");
            }
		}
           
        return $helper;
    }
}