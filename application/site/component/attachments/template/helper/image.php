<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Image Template Helper
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Component\Attachments
 */
class AttachmentsTemplateHelperImage extends Library\TemplateHelperDefault
{
    public function thumbnail($config = array())
    {
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
            'attachment' => false,
            'attribs' => array()
        ));

        //Make sure the attachment is set
        if($config->attachment) {
            $thumbnail = $this->getObject('com:attachments.database.row.attachment')->set('id', $config->attachment)->load();

            //Make sure the thumbnail exist
            if($thumbnail) {
                $filename = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', pathinfo($thumbnail->name, PATHINFO_FILENAME)));

                return '<img alt="'.$filename.'" '.$this->buildAttributes($config->attribs).' src="attachments://'.$thumbnail->thumbnail.'" />';
            }
        }

        return false;
    }
}