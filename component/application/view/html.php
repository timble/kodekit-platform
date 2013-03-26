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
 * Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewHtml extends Framework\ViewHtml
{
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $path  = $this->getService('request')->getBaseUrl()->getPath();
        $path .= '/theme/'.$this->getService('application')->getTheme().'/';
        $this->getTemplate()->getFilter('alias')->addAlias(
            array($this->_mediaurl.'/application/' => $path), Framework\TemplateFilter::MODE_READ | Framework\TemplateFilter::MODE_WRITE
        );
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'auto_assign' => false,
            'template_filters' => array('script', 'style', 'link', 'meta'),
        ));

        parent::_initialize($config);
    }

    public function render()
    {
        //Set the language information
        $this->language  = \JFactory::getLanguage()->getTag();
        $this->direction = \JFactory::getLanguage()->isRTL() ? 'rtl' : 'ltr';

        return parent::render();
    }
}