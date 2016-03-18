<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Pages;

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Breadcrumbs Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Pages
 */
class ModuleBreadcrumbsHtml extends Pages\ModuleAbstract
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $pathway = $this->getObject('pages')->getPathway()->getArrayCopy();

        if($context->parameters->get('homeText'))
        {
            $default    = $this->getObject('pages')->getDefault();
            $translator = $this->getObject('translator');

            array_unshift($pathway, array(
                'title' => $context->parameters->get('homeText', $translator('Home')),
                'link'  => $default->getLink()->getQuery()
            ));
        }

        $context->data->pathway = $pathway;

        parent::_fetchData($context);
    }

    /**
     * Renders and echo's the module output
     *
     * @return string  The output of the module
     */
    protected function _actionRender(Library\ViewContext $context)
    {
        $result = '';
        $pages = $this->getObject('pages');

        if($active = $pages->getActive())
        {
            $default = $pages->getDefault();
            if ($active->id != $default->id) {
                $result  = parent::_actionRender($context);
            }
        }

        return $result;
    }
}