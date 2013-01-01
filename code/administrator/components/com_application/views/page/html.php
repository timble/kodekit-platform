<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComApplicationViewPageHtml extends ComApplicationViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'template_filters' => array(
                'expire',
                'com://admin/pages.template.filter.module'
            ),
        ));

        parent::_initialize($config);
    }

    public function display()
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

        return parent::display();
    }
}