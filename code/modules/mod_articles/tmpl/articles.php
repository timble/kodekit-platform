<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @author         Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access'); ?>

<div class="mod_articles<?php echo $params->get('moduleclass_sfx'); ?>">
    <?php foreach ($articles as $article): ?>
    <?php echo @helper('com://site/articles.template.helper.article.render',
        array(
            'row'              => $article,
            'show_create_date' => false,
            'show_modify_date' => false,
            'show_images'      => false,
            'title_heading'    => 3));?>
    <?php endforeach; ?>
</div>