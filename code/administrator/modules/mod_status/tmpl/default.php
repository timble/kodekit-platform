<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<? $class = JRequest::getInt('hidemainmenu') ? "class='disabled'" : ""; ?>

<ul id="statusmenu">
<li class="preview">
	<a href="<?= JURI::root() ?>" target="_blank"><?= @text('Preview') ?></a>
</li>

<li <?= $class  ?>>
	<a href="<?= JRoute::_('index.php?option=com_users&view=user&id='.JFactory::getUser()->id) ?>"><?= @text('My Profile') ?></a>
</li>

<? if(!strpos(KRequest::get('server.HTTP_USER_AGENT', 'word'), 'Titanium')) : ?>

    <? $json = "{method:'post', url:'index.php?option=com_users&view=user&id=".JFactory::getUser()->id."', params:{action:'logout', _token:'".JUtility::getToken()."'}}"; ?>
    <li <?= $class ?>>
    	<a href="#" onclick="new Koowa.Form(<?= $json ?>).submit();"><?= @text('Logout') ?></a>
    </li>
<? endif; ?>
</ul>