<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Decoratable Module Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\View
 */
class ModuleBehaviorDecoratable extends Library\ViewBehaviorAbstract
{
    /**
     * Decorate the module
     *
     * @param Library\ViewContextInterface $context	A view context object
     * @return 	void
     */
    protected function _afterRender(Library\ViewContextInterface $context)
    {
        if($context->parameters->decorator)
        {
            $decorators = Library\ObjectConfig::unbox($context->parameters->decorator);

            if(is_string($decorators)) {
                $decorators = preg_split('/\s+/', $context->parameters->decorator);
            }

            foreach($decorators as $decorator)
            {
                if(strpos($decorator, '.') === false)
                {
                    $layout = 'mod:pages.'.trim($decorator);
                    $layout = $layout.'.'.$this->getFormat();
                }
                else $layout = $decorator;

                //Unpack the data (first level only)
                $data = $context->data->toArray();

                $context->result = $this->getTemplate()
                    ->loadFile($layout)
                    ->setParameters($context->parameters)
                    ->render($data);
            }
        }
    }
}