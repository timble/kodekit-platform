<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="sidebar">
    <h3><?= @text('Sections') ?></h3>
    <?= @template('admin::com.articles.view.sections.list', array('state' => $state, 'sections' => KFactory::tmp('admin::com.articles.model.sections')->getList())); ?>
</div>