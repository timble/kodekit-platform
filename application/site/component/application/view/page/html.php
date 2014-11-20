<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Html Page View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationViewPageHtml extends Application\ViewPageHtml
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'template_filters' => array('com:files.template.filter.files', 'com:attachments.template.filter.attachments'),
        ));

        parent::_initialize($config);
    }

    /**
     * Get the title
     *
     * @return 	string 	The title of the view
     */
    public function getTitle()
    {
        //Get the parameters of the active menu item
        $page = $this->getObject('application.pages')->getActive();
        return $page->title;
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        //Set the component and layout information
        if($this->getObject('manager')->isRegistered('dispatcher'))
        {
            $context->data->component = $this->getObject('dispatcher')->getIdentifier()->package;
            $context->data->layout    = $this->getObject('dispatcher')->getController()->getView()->getLayout();
        }
        else
        {
            $context->data->component = '';
            $context->data->layout    = '';
        }

        parent::_fetchData($context);
    }
}