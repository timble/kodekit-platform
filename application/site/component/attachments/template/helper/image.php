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
            'attribs' => array()
        ));

        $thumbnail = $this->getObject('com:attachments.model.attachment')->id($config->attachment)->getRow()->thumbnail;

        if($thumbnail) {
            return '<img '.$this->buildAttributes($config->attribs).' src="attachments://'.$thumbnail.'" />';
        }

        return false;
    }
}