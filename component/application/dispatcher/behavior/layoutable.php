<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Layoutable Dispatcher Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class DispatcherBehaviorLayoutable extends Library\DispatcherBehaviorAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config A ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_LOW,
        ));

        parent::_initialize($config);
    }

    /**
     * Check if the behavior is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $mixer   = $this->getMixer();
        $request = $mixer->getRequest();

        if($request->getFormat() == 'html' && $request->isGet()) {
            return true;
        }

        return false;
    }

    /**
     * Get the layout
     *
     * @return string The page layout
     */
    public function getLayout()
    {
        $response = $this->getResponse();
        $request  = $this->getRequest();

        if($response->isError())
        {
            $layout = 'error';
            if($response->getStatusCode() == Library\HttpResponse::UNAUTHORIZED) {
                $layout = 'error_401';
            }
        }
        else $layout = $request->query->get('tmpl', 'cmd', 'default');

        return $layout;
    }

    /**
     * Render the page
     *
     * @param 	Library\DispatcherContextInterface $context The active command context
     * @return 	void
     */
    protected function _beforeSend(Library\DispatcherContextInterface $context)
    {
        $response = $context->getResponse();
        $request  = $context->getRequest();

        if(!$response->isDownloadable())
        {
            $layout      = $this->getLayout();
            $application = $this->getObject('application');

            $application->getController()
                ->layout($layout)
                ->render();
        }
    }
}