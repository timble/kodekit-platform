<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Menubar Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
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