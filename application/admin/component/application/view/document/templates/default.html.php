<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>">

<?= import('document_head.html') ?>

<body class="com_<?= $component ?>">
<div id="panel-wrapper">
    <div id="panel-navbar">
        <div id="menu">
            <ktml:toolbar type="menubar">
        </div>
        <ktml:toolbar type="actionbar" id="statusmenu">
	</div>

    <div id="panel-tabbar">
        <ktml:toolbar type="tabbar">
    </div>

    <ktml:block name="actionbar">
    <div id="panel-toolbar">
        <ktml:block:content>
        <ktml:messages>
    </div>
    </ktml:block>

    <div id="panel-component">
        <ktml:block name="sidebar">
        <div id="panel-sidebar">
            <ktml:block:content>
        </div>
        </ktml:block>

        <div id="panel-content">
            <ktml:content>
	    </div>
    </div>
</div>

</body>

</html>