<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">
<?= import('page_head.html') ?>

<body>
<header class="container">
    <div class="navbar">
        <nav class="navbar-inner">           
            <a class="brand" href="/"><?= escape(object('application')->getCfg('sitename' )) ?></a>
            <div>
                <ktml:modules position="user3">
            </div>
            <ktml:modules position="user4">
        </nav>
    </div>
</header>

<div class="container">
    <div class="row">
        <aside class="sidebar span3">
            <div class="well" style="padding: 8px 0;">
            	<ktml:modules position="left" chrome="wrapped">
            </div>
        </aside>
        <div class="span9">
            <ktml:modules position="breadcrumb">
            <?= import('page_message.html') ?>
            <section>
                <ktml:content>
            </section>
        </div>
    </div>
</div>

<? if(object('application')->getCfg('debug')) : ?>
    <?= object('com:debug.controller.debug')->render(); ?>
<? endif; ?>

</body>
</html>