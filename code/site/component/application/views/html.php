<?php
/**
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

class ComApplicationViewHtml extends KViewHtml
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $path = (string) KRequest::base().'/site/public/theme/'.$this->getService('application')->getTheme().'/';
        $this->getTemplate()->getFilter('alias')->addAlias(
            array($this->_mediaurl.'/application/' => $path), KTemplateFilter::MODE_READ | KTemplateFilter::MODE_WRITE
        );
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_assign' => false,
            'template_filters' => array('script', 'style', 'link', 'meta'),
        ));

        parent::_initialize($config);
    }

    public function display()
    {
        //Set the language information
        $this->language  = JFactory::getLanguage()->getTag();
        $this->direction = JFactory::getLanguage()->isRTL() ? 'rtl' : 'ltr';

        return parent::display();
    }

    public function getLayout()
    {
        return 'default';
    }

}