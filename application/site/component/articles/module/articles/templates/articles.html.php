<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<? if($show_title) : ?>
<h3><?= $module->title ?></h3>
<? endif ?>

<?php foreach ($articles as $article): ?>
<?php echo @helper('com:articles.article.render',
    array(
        'row'              => $article,
        'show_create_date' => false,
        'show_modify_date' => false,
        'show_images'      => false,
        'title_heading'    => 3));?>
<?php endforeach; ?>
