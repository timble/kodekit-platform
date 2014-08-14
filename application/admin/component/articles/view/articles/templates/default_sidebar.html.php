<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<h3><?= translate('Categories')?></h3>
<?= import('com:articles.view.categories.list.html', array('categories' => object('com:articles.model.categories')->sort('title')->table('articles')->fetch())); ?>