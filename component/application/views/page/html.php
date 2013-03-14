<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Application;

use Nooku\Framework;

/**
 * Html Page View
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewPageHtml extends ViewHtml
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'template_filters' => array('expire','module'),
        ));

        parent::_initialize($config);
    }

    public function render()
    {
        // Build the sorted message list
        $messages = $this->getService('application')->getMessageQueue();
        if (is_array($messages) && count($messages))
        {
            foreach ($messages as $message)
            {
                if (isset($message['type']) && isset($message['message'])) {
                    $this->messages[$message['type']][] = $message['message'];
                }
            }
        }
        else  $this->messages = array();

        //Set the component and layout information
        $this->component = $this->getService('application')->getController()->getIdentifier()->package;
        $this->layout    = $this->getService('component')->getController()->getView()->getLayout();

        return parent::render();
    }
}