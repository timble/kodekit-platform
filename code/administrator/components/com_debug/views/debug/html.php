<?php
/**
 * @version     $Id: debug.php 783 2011-07-13 20:40:12Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Debug Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
class ComDebugViewDebugHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $profiler = KFactory::get('com://admin/debug.profiler.events');
        $database = KFactory::get('com://admin/debug.profiler.queries');
        $language = KFactory::get('joomla:language');
        
        //Remove the template includes
        $includes = get_included_files();
        
        foreach($includes as $key => $value)
        {
            if($value == 'tmpl://koowa.template.stack') {
                unset($includes[$key]);
            }
        }
	    
	    $this->assign('memory'   , $profiler->getMemory())
	         ->assign('events'   , $profiler->getEvents())
	         ->assign('queries'  , $database->getQueries())
	         ->assign('languages', $language->getPaths())
	         ->assign('includes' , $includes)
	         ->assign('strings'  , $language->getOrphans());
                        
        return parent::display();
    }
}