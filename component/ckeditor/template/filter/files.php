<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;

/**
 * Url Template Filter
 *
 * Filter rewrites relative files/... paths as inserted by the editor to absolute paths /files/[site]/files/...
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Ckeditor
 */
class TemplateFilterFiles extends Library\TemplateFilterUrl
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        //Make images paths absolute
        $base = $this->getObject('request')->getBaseUrl();
        $site = $this->getObject('application')->getSite();

        $path = $base->getPath().'/files/'.$site.'/files/';

        $config->append(array(
            'aliases' => array('files/'  => $path)
        ));

        parent::_initialize($config);
    }
}