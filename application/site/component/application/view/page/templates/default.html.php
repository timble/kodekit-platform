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
<?= import('page_head.html') ?>

<body>
<header class="container">
    <nav class="navbar navbar-default">
        <a class="navbar-brand" href="/"><?= escape(object('application')->getTitle()) ?></a>
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