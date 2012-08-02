<?
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<form action="" method="get" name="adminForm">
<? foreach ($weblinks as $weblink) : ?>

    <h2><?= @escape($weblink->title) ?></h2>

    <? if ( $params->get( 'show_link_description' ) ) : ?>
		<p><?= nl2br(@escape($weblink->description)); ?></p>
	<? endif; ?>

    <a href="<?= @helper('route.weblink', array('row' => $weblink)) ?>" class="<?= 'category'.@escape($params->get( 'pageclass_sfx' )); ?>">
        <?= @escape($weblink->url) ?>
    </a>

    <? endforeach; ?>

<?= @helper('paginator.pagination', array('total' => $total)) ?>
</form>
