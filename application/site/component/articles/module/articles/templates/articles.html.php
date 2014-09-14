<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<? if($module->getParameters()->get('show_title', false)) : ?>
<h3><?= $module->title ?></h3>
<? endif ?>

<?php foreach ($articles as $article): ?>
<?php echo helper('com:articles.article.render',
    array(
        'entity'              => $article,
        'show_create_date' => false,
        'show_modify_date' => false,
        'show_images'      => false,
        'title_heading'    => 3));?>
<?php endforeach; ?>
