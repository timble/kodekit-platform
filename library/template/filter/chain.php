<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Chain Template Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterChain extends ObjectQueue implements TemplateFilterRenderer, TemplateFilterCompiler
{
    /**
     * Parse the text and compile it
     *
     * @param string $text  The text string to parse
     * @return void
     */
    public function compile(&$text)
    {
        foreach($this as $filter)
        {
            if($filter instanceof TemplateFilterCompiler) {
                $filter->compile($text);
            }
        }
    }

    /**
     * Parse the text and render it
     *
     * @param string $text  The text string to parse
     * @return void
     */
    public function render(&$text)
    {
        foreach($this as $filter)
        {
            if($filter instanceof TemplateFilterRenderer) {
                $filter->render($text);
            }
        }
    }

    /**
     * Attach a filter to the queue
     *
     * The priority parameter can be used to override the filter priority while enqueueing the filter.
     *
     * @param   TemplateFilterInterface  $filter
     * @param   integer          $priority The filter priority, usually between 1 (high priority) and 5 (lowest),
     *                                     default is 3. If no priority is set, the filter priority will be used
     *                                     instead.
     * @return TemplateFilterChain
     * @throws \InvalidArgumentException if the object doesn't implement TemplateFilterInterface
     */
    public function enqueue(ObjectHandlable $filter, $priority = null)
    {
        if (!$filter instanceof TemplateFilterInterface) {
            throw new \InvalidArgumentException('Filter needs to implement TemplateFilterInterface');
        }

        $priority = is_int($priority) ? $priority : $filter->getPriority();
        return parent::enqueue($filter, $priority);
    }

    /**
     * Removes a filter from the queue
     *
     * @param   TemplateFilterInterface   $filter
     * @return  boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement TemplateFilterInterface
     */
    public function dequeue(ObjectHandlable $filter)
    {
        if (!$filter instanceof TemplateFilterInterface) {
            throw new \InvalidArgumentException('Filter needs to implement TemplateFilterInterface');
        }

        return parent::dequeue($filter);
    }

    /**
     * Check if the queue does contain a given filter
     *
     * @param  TemplateFilterInterface   $filter
     * @return bool
     * @throws \InvalidArgumentException if the object doesn't implement TemplateFilterInterface
     */
    public function contains(ObjectHandlable $filter)
    {
        if (!$filter instanceof TemplateFilterInterface) {
            throw new \InvalidArgumentException('Filter needs to implement TemplateFilterInterface');
        }

        return parent::contains($filter);
    }
}