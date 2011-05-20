<?php
/**
 * @version     $Id: module.php 632 2011-03-20 14:28:45Z cristiano.cucco $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" border="0" width="<?= $banner->width?>" height="<?= $banner->height?>">
<param name="movie" value="images/banners/<?= $banner->imageurl?>">
<embed src="images/banners/<?= $banner->imageurl?>" loop="false" pluginspage="http://www.macromedia.com/go/get/flashplayer" type="application/x-shockwave-flash" width="<?= $banner->width?>" height="<?= $banner->height?>"></embed>
</object>
  