<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Image Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Attachments
 */
class AttachmentsTemplateHelperImage extends Library\TemplateHelperAbstract
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