<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Debug;

use Nooku\Library;

/**
 * Debug Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Debug
 */
class ViewDebugHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $database = $this->getObject('com:debug.event.subscriber.database');
        $profiler = $this->getObject('com:debug.event.profiler');
        //$language = \JFactory::getLanguage();

        //Remove the template includes
        $includes = get_included_files();

        foreach($includes as $key => $value)
        {
            //Find the real file path
            if($alias = Library\ClassLoader::getInstance()->getAlias($value)) {
                $includes[$key] = $alias;
            };
        }

	    $context->data->memory    = $profiler->getMemory();
	    $context->data->events    = (array) $profiler->getEvents();
	    $context->data->queries   = (array) $database->getQueries();
	    $context->data->languages = array(); // $language->getPaths();
	    $context->data->includes  = (array) $includes;
	    $context->data->strings   = array(); //$language->getOrphans();

        parent::_fetchData($context);
    }
}