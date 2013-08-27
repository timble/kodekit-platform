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
 * Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewHtml extends Library\ViewHtml
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $path  = $this->getObject('request')->getBaseUrl()->getPath();
        $path .= '/theme/'.$this->getObject('application')->getTheme().'/';

        $this->getTemplate()->getFilter('url')->addAlias('/assets/application/', $path);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_assign'      => false,
            'template_filters' => array('script', 'style', 'link', 'meta', 'title'),
        ));

        parent::_initialize($config);
    }

    public function render()
    {
        //Set the language information
        $this->language  = \JFactory::getLanguage()->getTag();
        $this->direction = \JFactory::getLanguage()->isRTL() ? 'rtl' : 'ltr';

        // Set the site information
        $this->site  = $this->getObject('application')->getSite();

        return parent::render();
    }
}