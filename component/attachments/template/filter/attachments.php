<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-attachments for the canonical source repository
 */

namespace Kodekit\Component\Attachments;

use Kodekit\Library;
use Kodekit\Component\Files;

/**
 * Url Template Filter
 *
 * Filter rewrites relative attachments/... paths as inserted by the editor to absolute paths /files/[site]/attachments/...
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Attachments
 */
class TemplateFilterAttachments extends Files\TemplateFilterFiles
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

        $path = $base->getPath().'/files/'.$site.'/attachments/';


        $config->append(array(
            'priority' => self::PRIORITY_LOWEST,
            'schemes' => array(
                'attachments://' => $path,
                '"attachments/'  => '"'.$path
            )
        ));

        parent::_initialize($config);
    }
}