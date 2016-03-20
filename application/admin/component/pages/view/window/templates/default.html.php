<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<title><?= title().' - '.translate( 'Administration'); ?></title>

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