<?php
/**
 * @version     $Id: html.php 1481 2012-02-10 01:46:24Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Default
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Random Image Module Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Modules
 * @subpackage  Default
 */
 
class ModImageHtml extends ModDefaultHtml
{
    public function display()
    {
        $link 	 = $this->params->get( 'link' );

        $folder	= $this-getFolder($this->params);
        $images	= $this->getImages($this->params, $folder);

        if (count($images))
        {
            $this->image = $this->getRandomImage($this->params, $images);
            return parent::display();
        }
    }

    public function getRandomImage($params, $images)
    {
        $width 		= $params->get( 'width' );
        $height 	= $params->get( 'height' );

        $i 				= count($images);
        $random 		= mt_rand(0, $i - 1);
        $image 			= $images[$random];
        $size 			= getimagesize (JPATH_IMAGES.DS.$image->folder .DS. $image->name);

        if ($width == '') {
            $width = 100;
        }

        if ($size[0] < $width) {
            $width = $size[0];
        }

        $coeff = $size[0]/$size[1];
        if ($height == '') {
            $height = (int) ($width/$coeff);
        } else {
            $newheight = min ($height, (int) ($width/$coeff));
            if ($newheight < $height) {
                $height = $newheight;
            } else {
                $width = $height * $coeff;
            }
        }

        $image->width 	= $width;
        $image->height	= $height;
        $image->folder	= 'images/'.str_replace( '\\', '/', $image->folder );

        return $image;
    }

    public function getImages($params, $folder)
    {
        $type 		= $params->get( 'type', 'jpg' );

        $files	= array();
        $images	= array();

        $dir = JPATH_IMAGES.DS.$folder;

        // check if directory exists
        if (is_dir($dir))
        {
            if ($handle = opendir($dir))
            {
                while (false !== ($file = readdir($handle)))
                {
                    if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html' ) {
                        $files[] = $file;
                    }
                }
            }
            closedir($handle);

            $i = 0;
            foreach ($files as $img)
            {
                if (!is_dir($dir .DS. $img))
                {
                    if (preg_match("#$type#i", $img)) {
                        $images[$i]->name 	= $img;
                        $images[$i]->folder	= $folder;
                        ++$i;
                    }
                }
            }
        }

        return $images;
    }

    public function getFolder($params)
    {
        $folder 	= $params->get( 'folder' );
        $LiveSite 	= JURI::base();

        // if folder includes livesite info, remove
        if ( JString::strpos($folder, $LiveSite) === 0 ) {
            $folder = str_replace( $LiveSite, '', $folder );
        }

        // if folder includes absolute path, remove
        if ( JString::strpos($folder, JPATH_ROOT) === 0 ) {
            $folder= str_replace( JPATH_BASE, '', $folder );
        }

        $folder = str_replace('\\',DS,$folder);
        $folder = str_replace('/',DS,$folder);

        return $folder;
    }
} 