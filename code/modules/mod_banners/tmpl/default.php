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

<div class="bannergroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">

<? if (trim( $params->get( 'header_text' ) )) : ?>
<div class="bannerheader"><?= trim( $params->get( 'header_text' ) ) ?></div>
<? endif; ?>

<? foreach($banners as $banner) : ?>
<div class="banneritem<?= $params->get( 'moduleclass_sfx' ) ?>">
	<?= @template('site::mod.banners.default_'.$banner->type, array('banner' => $banner)); ?>
	<div class="clr"></div>
</div>
<?php endforeach; ?>

<? if (trim( $params->get( 'footer_text' ) )) : ?>
<div class="bannerfooter<?$params->get( 'moduleclass_sfx' ) ?>">
    <?= trim( $params->get( 'footer_text' ) ) ?>
</div>
<? endif; ?>
</div>