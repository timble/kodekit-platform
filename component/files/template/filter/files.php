<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Files Template Filter
 *
 * Filter rewrites relative files/... paths as inserted by the editor to absolute paths /files/[site]/files/...
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Ckeditor
 */
class TemplateFilterFiles extends Library\TemplateFilterAsset
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
            'aliases' => array(
                'files://' => $path,
                '"files/'  => '"'.$path
            )
        ));

        parent::_initialize($config);
    }
}