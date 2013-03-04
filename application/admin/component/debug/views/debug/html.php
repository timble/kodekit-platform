<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
class ComDebugViewDebugHtml extends KViewHtml
{
    public function render()
    {
        $database = $this->getService('com://admin/debug.event.subscriber.database');
        $profiler = $this->getService('com://admin/debug.event.profiler');
        $language = JFactory::getLanguage();

        //Remove the template includes
        $includes = get_included_files();

        foreach($includes as $key => $value)
        {
            //Find the real file path
            if($alias = $this->getService('loader')->getAlias($value)) {
                $includes[$key] = $alias;
            };
        }

	    $this->memory    = $profiler->getMemory();
	    $this->events    = (array) $profiler->getEvents();
	    $this->queries   = (array) $database->getQueries();
	    $this->languages = (array) $language->getPaths();
	    $this->includes  = (array) $includes;
	    $this->strings   = (array) $language->getOrphans();
                        
        return parent::render();
    }
}