<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;

/**
 * Route Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
 */
class TemplateHelperRoute extends Library\TemplateHelperAbstract
{
    public function route($route)
    {
        if(isset($route['component'])) {
            $route['component'] = $this->getIdentifier()->package;
        }

        $route = $this->getObject('lib:dispatcher.router.route', array('escape' =>  true))
            ->setQuery($route);

        return $route;
    }

    /**
     * Find a page based on list of needles
     *
     * @param array $needles   An associative array of needles
     * @return
     */
    protected function _findPage($needles)
    {
        $pages  = $this->getObject('pages');
        return $pages->find(array('component' => $this->getIdentifier()->package, 'link' => $needles));
    }
}