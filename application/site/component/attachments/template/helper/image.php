<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Attachments;

use Kodekit\Library;

/**
 * Image Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Platform\Attachments
 */
class TemplateHelperImage extends Library\TemplateHelperAbstract
{
    public function thumbnail($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'attachment' => false,
            'attribs'    => array()
        ));

        //Make sure the attachment is set
        if ($config->attachment)
        {
            $thumbnail = $this->getObject('com:attachments.model.attachments')
                ->id($config->attachment)
                ->fetch();

            //Make sure the thumbnail exist
            if ($thumbnail)
            {
                $filename = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', pathinfo($thumbnail->name, PATHINFO_FILENAME)));
                return '<img alt="' . $filename . '" ' . $this->buildAttributes($config->attribs) . ' src="attachments://' . $thumbnail->thumbnail . '" />';
            }
        }

        return false;
    }
}