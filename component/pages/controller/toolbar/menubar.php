<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Menubar Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class ControllerToolbarMenubar extends Library\ControllerToolbarAbstract
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'type'        => 'menubar',
            'show_hidden' => false,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands()
    {
        $menu = $this->getObject('pages.menus')->find(array('slug' => 'menubar'));

        if(count($menu))
        {
            $component = $this->getObject('dispatcher')->getIdentifier()->package;

            $queue = new \SplStack();
            $queue->push($this);

            $iterator = $this->getObject('pages')->getRecursiveIterator();
            foreach($iterator as $page)
            {
                if($page->canAccess())
                {
                    $command = $queue->top()->addCommand($page->title, array(
                        'id'       => $page->id,
                        'href'     => $page->getLink(),
                        'active'   => (string) $component == $page->component,
                        'path'     => $page->getPath(),
                        'hidden'   => $page->hidden,
                    ));

                    if(!$page->hasChildren())
                    {
                        if(!$iterator->hasNext()) {
                            $queue->pop();
                        }
                    }
                    else $queue->push($command);
                }
            }
        }

        return parent::getCommands();
    }
}