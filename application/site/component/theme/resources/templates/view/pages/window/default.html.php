<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<header class="container">
    <nav class="navbar navbar-default">
        <a class="navbar-brand" href="/"><?= escape(object('pages')->getActive()->title) ?></a>
        <div>
            <ktml:block name="user3">
        </div>
        <ktml:block name="user4">
    </nav>
</header>

<div class="container">
    <div class="row">
        <aside class="sidebar col-md-3">
            <ktml:block name="left" decorator="wrapper">
        </aside>
        <div class="col-md-9">
            <ktml:block name="breadcrumb">
            <ktml:messages>
            <section>
                <ktml:content>
            </section>
        </div>
    </div>
</div>