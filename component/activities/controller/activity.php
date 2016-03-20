<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Activity Controller.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
class ControllerActivity extends Library\ControllerModel
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config Configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getObject('translator')->load('com:activities');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('purgeable')
        ));

        if ($this->getIdentifier()->getPackage() != 'activities')
        {
            $aliases = array(
                'com:activities.model.activities'               => array(
                    'path' => array('model'),
                    'name' => Library\StringInflector::pluralize($this->getIdentifier()->getName())
                ),

                'com:activities.controller.behavior.purgeable'  => array(
                    'path' => array('controller', 'behavior'),
                    'name' => 'purgeable'
                ),

                'com:activities.controller.permission.activity' => array(
                    'path' => array('controller', 'permission')
                ),

                'com:activities.controller.toolbar.activity'    => array(
                    'path' => array('controller', 'toolbar')
                )
            );

            foreach ($aliases as $identifier => $alias)
            {
                $alias = array_merge($this->getIdentifier()->toArray(), $alias);

                $manager = $this->getObject('manager');

                // Register the alias if a class for it cannot be found.
                if (!$manager->getClass($alias, false)) {
                    $manager->registerAlias($identifier, $alias);
                }
            }
        }

        parent::_initialize($config);
    }

    /**
     * Method to set a view object attached to the controller
     *
     * @param   mixed   $view An object that implements Library\ObjectInterface, Library\ObjectIdentifier object
     *                  or valid identifier string
     * @return  object  A Library\ViewInterface object or a Library\ObjectIdentifier object
     */
    public function setView($view)
    {
        $view   = parent::setView($view);
        $format = $this->getRequest()->getFormat();

        if ($view instanceof Library\ObjectIdentifier && $view->getPackage() != 'activities' && $format  !== 'html')
        {
            $manager = $this->getObject('manager');

            // Set the view identifier as an alias of the component view.
            if (!$manager->getClass($view, false))
            {
                $identifier = $view->toArray();
                $identifier['package'] = 'activities';
                unset($identifier['domain']);

                $manager->registerAlias($identifier, $view);
            }
        }

        return $view;
    }

    /**
     * Overridden for forcing the package model state.
     */
    public function getRequest()
    {
        $request = parent::getRequest();

        // Force set the 'package' in the request
        $request->query->package = $this->getIdentifier()->package;

        return $request;
    }

    /**
     * Set the IP address if we are adding a new activity.
     *
     * @param Library\ControllerContextInterface $context A command context object.
     * @return Library\ModelEntityInterface
     */
    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        $context->request->data->ip = $this->getObject('request')->getAddress();
    }
}
