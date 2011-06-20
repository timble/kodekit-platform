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

<? if (trim($banner->clickurl)) : ?>
<a href="<?=@route('view=banner&id='.$banner->id)?>" title="<?= $banner->name ?>">
<? endif; ?>
<img src="images/banners/<?= $banner->imageurl?>" alt="<?= $banner->name?>" width="<?= $banner->width?>" height="<?= $banner->height?>" />
<? if (trim($banner->clickurl)) : ?>
</a>
<? endif; ?>    