<?php
/**
 * @package        Nooku_Server
 * @subpackage     Categories
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Categories Template Helper Class
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package    Nooku_Server
 * @subpackage Categories
 */
class CategoriesTemplateHelperString extends Library\TemplateHelperDefault
{
    public function image($config = array())
    {
        $config   = new Library\ObjectConfig($config);
        $config->append(array(
           'align' => 'right',
           'class' => 'thumbnail'
        ));
        
        //Set the category image
        if (isset( $config->row->image ) && !empty($config->row->image))
        {
            $path = JPATH_IMAGES.'/stories/'.$config->row->image;
            $size = getimagesize($path);

            $image = (object) array(
                'path'   => '/'.str_replace(JPATH_ROOT.DS, '', $path),
                'width'  => $size[0],
                'height' => $size[1]
            );
        }
        
        $html = '<img class="'.$config->class.'" align="'.$config->align.'" src="'.$image->path.'" height="'.$image->height.'" width="'.$image->width.'" />';

        return $html;
    }
}