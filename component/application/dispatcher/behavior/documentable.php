<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Documentable Dispatcher Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class DispatcherBehaviorDocumentable extends Library\DispatcherBehaviorAbstract
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

        if(!$response->isDownloadable())
        {
            $layout = $this->getLayout();
            $config = array('response' => array(
                'content' => $response->getContent()
            ));

            $result = $this->getObject('com:application.controller.document', $config)
                ->layout($layout)
                ->render();

            $response->setContent($result);
        }
    }
}