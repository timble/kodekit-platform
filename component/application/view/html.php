<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
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
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewHtml extends Library\ViewHtml
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $path  = $this->getObject('request')->getBaseUrl()->getPath();
        $path .= '/theme/'.$this->getObject('application')->getTheme().'/';

        $this->getTemplate()->getFilter('asset')->addScheme('/assets/application/', $path);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_fetch'       => false,
        ));

        parent::_initialize($config);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        //Set the language information
        $context->data->language  = $this->getObject('translator')->getLocale();

        // Set the site information
        $context->data->site  = $this->getObject('application')->getSite();

        parent::_fetchData($context);
    }
}