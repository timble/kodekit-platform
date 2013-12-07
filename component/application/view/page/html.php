<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Html Page View
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewPageHtml extends ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'template_filters' => array('module'),
        ));

        parent::_initialize($config);
    }

    public function fetchData(Library\ViewContext $context)
    {
        // Build the sorted message list
        $context->data->messages = $this->getObject('response')->getMessages();

        //Set the component and layout information
        $context->data->extension = $this->getObject('component')->getIdentifier()->package;
        $context->data->layout    = $this->getObject('component')->getController()->getView()->getLayout();

        return parent::fetchData($context);
    }
}