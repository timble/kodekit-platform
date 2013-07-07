<?php
/**
 * @package        Nooku_Server
 * @subpackage     Attachments
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Attachments Template Helper Class
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package    Nooku_Server
 * @subpackage Attachments
 */
class AttachmentsTemplateHelperImage extends Library\TemplateHelperDefault
{
    public function thumbnail($config = array())
    {
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
            'align' => 'right',
            'class' => 'thumbnail article__thumbnail'
        ));

        $image = $config->row;

        if($image->thumbnail) {
            return '<img class="'.$config->class.'" align="'.$config->align.'" src="'.$image->thumbnail.'" />';
        }

        return false;
    }
}