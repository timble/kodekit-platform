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
<html lang="<?= $language; ?>">
<?= import('page_head.html') ?>

<body>
<header class="container">
    <nav class="navbar navbar-default">
        <a class="navbar-brand" href="/"><?= escape(object('application')->getCfg('sitename' )) ?></a>
        <div>
            <ktml:modules position="user3">
        </div>
        <ktml:modules position="user4">
    </nav>
</header>

<div class="container">
    <div class="row">
        <aside class="sidebar col-md-3">
            <ktml:modules position="left" chrome="wrapped">
        </aside>
        <div class="col-md-9">
            <ktml:modules position="breadcrumb">
            <ktml:messages>
            <section>
                <ktml:content>
            </section>
        </div>
    </div>
</div>

</body>
</html>