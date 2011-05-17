<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ($params->get( 'show_page_title', 1 )) : ?>
<div class="componentheading<?= @escape( $params->get( 'pageclass_sfx' ) );?>">
	<?= $params->get( 'page_title', @text( 'Search' ) );?>
</div>
<? endif; ?>

<?= @template( 'default_form' );?>

<?if (count($results)) :?>
    <?= @template( 'default_results' );?>
<? endif;?>

