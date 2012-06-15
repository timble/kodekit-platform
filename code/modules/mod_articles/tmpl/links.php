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

<ul class="mod_articles<?php echo $params->get('moduleclass_sfx'); ?>">
    <?php foreach ($articles as $article): ?>
    <li class="mod_articles<?php echo $params->get('moduleclass_sfx'); ?>">
        <?php echo @helper('com://site/articles.template.helper.article.link',
        array(
            'row'     => $article,
            'text'    => @escape($article->title),
            'attribs' => array('class' => 'mod_articles' . $params->get('moduleclass_sfx'))));?>
    </li>
    <?php endforeach; ?>
</ul>