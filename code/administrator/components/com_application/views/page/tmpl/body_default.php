<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://com_application/js/application.js" />

<? /*if($params->get('flexBox', '1')) :*/ if(true) : ?>
<script src="media://com_application/js/chromatable.js" />
<? else : ?>
<style src="media://com_application/css/legacy.css" />
<? endif; ?>

<body id="minwidth-body" class="<?= JRequest::getVar('option', 'cmd'); ?>">
<div id="container">

    <div id="header-box">

        <?= @helper('menubar.render', array('menubar' => $menubar, 'attribs' => array('id' => 'menu')))?>
        <?= @helper('toolbar.render', array('toolbar' => $toolbar, 'attribs' => array('id' => 'statusmenu')))?>

	</div>

    <div id="tabs-box">
		<ktml:modules position="menubar" />
	</div>

    <ktml:modules position="toolbar">
    <div id="toolbar-box">
        <ktml:content />
    </div>
    </ktml:modules>

    <?= @template('default_message') ?>

    <div id="window-body" class="<?= @service('dispatcher')->getController()->getView()->getLayout() ?>">

        <ktml:modules position="sidebar">
        <div id="window-sidebar">
            <ktml:content />
        </div>
        </ktml:modules>

        <div id="window-content" class="<?= @service('dispatcher')->getController()->getView()->getLayout() ?> row-fluid">
            <ktml:variable name="component" />
	    </div>

        <ktml:modules position="inspector">
            <div id="window-inspector">
                <ktml:content />
            </div>
        </ktml:modules>

    </div>
</div>
<? if(KDEBUG) : ?>
	<?= @service('com://admin/debug.controller.debug')->display(); ?>
<? endif; ?>
	
<script data-inline src="media://com_application/js/chosen.mootools.1.2.js" /></script>
<script data-inline> $$(".chzn-select").chosen(); </script>
</body>