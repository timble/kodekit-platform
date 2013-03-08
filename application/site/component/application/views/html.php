<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComApplicationViewHtml extends Framework\ViewHtml
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
        $this->language  = JFactory::getLanguage()->getTag();
        $this->direction = JFactory::getLanguage()->isRTL() ? 'rtl' : 'ltr';

        return parent::render();
    }
}