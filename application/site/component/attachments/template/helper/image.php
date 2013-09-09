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
            'align' => 'right',
            'class' => ''
        ));

        $image = $config->row;

        if($image->thumbnail) {
            return '<figure><img class="'.$config->class.'" align="'.$config->align.'" src="'.$image->thumbnail.'" /></figure>
';
        }

        return false;
    }
}