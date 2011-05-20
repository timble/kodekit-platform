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

<? 
$html = str_replace( '{CLICKURL}', @route('view=banner&id='.$banner->id), $banner->custombannercode );
$html = str_replace( '{NAME}', $banner->name, $html );
      
echo $html;