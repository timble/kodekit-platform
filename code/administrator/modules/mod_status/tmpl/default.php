<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://lib_koowa/js/koowa.js" />

<ul id="statusmenu">
<li class="preview">
	<a href="<?= JURI::root() ?>" target="_blank"><?= @text('Preview') ?></a>
</li>

<li>
	<a href="<?= JRoute::_('index.php?option=com_users&view=user&id='.JFactory::getUser()->id) ?>"><?= @text('My Profile') ?></a>
</li>

<? if(!strpos(KRequest::get('server.HTTP_USER_AGENT', 'word'), 'Titanium')) : ?>

    <? $json = "{method:'post', url:'index.php?option=com_users&view=session&id=".@service('application.session')->getId()."', params:{action:'delete', _token:'".@service('application.session')->getToken()."'}}"; ?>
    <li>
    	<a href="#" onclick="new Koowa.Form(<?= $json ?>).submit();"><?= @text('Logout') ?></a>
    </li>
<? endif; ?>
</ul>