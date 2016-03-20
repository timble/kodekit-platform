<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-deugger for the canonical source repository
 */

namespace Kodekit\Component\Debug;

use Kodekit\Library;

/**
 * Debug Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Debug
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