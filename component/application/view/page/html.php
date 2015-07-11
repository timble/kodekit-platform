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
 * Html Page View
 *
 * @author      Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewPageHtml extends ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'template_filters'	=> array('style', 'link', 'meta', 'script', 'title', 'message'),
        ));

        parent::_initialize($config);
    }

    public function getTitle()
    {
        return $this->getObject('application')->getTitle();
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        //Set the component and layout information
        if($this->getObject('manager')->isRegistered('dispatcher')) {
            $context->data->component = $this->getObject('dispatcher')->getIdentifier()->package;
        } else {
            $context->data->component = '';
        }

        parent::_fetchData($context);
    }
}