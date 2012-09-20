<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<div class="page-header">
    <h1><?= $params->get( 'page_title', @text( 'Search' ) );?></h1>
</div>

<?= @template('com://site/search.view.results.default_form');?>

<?if (count($results)) :?>
    <?= @template('com://site/search.view.results.default_results');?>
<? endif;?>

