<?
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<header class="container">
    <div class="navbar">
        <nav class="navbar-inner">           
            <a class="brand" href="/"><?= @escape(@service('application')->getCfg('sitename' )) ?></a>
            <div>
                <ktml:modules position="user3" />
            </div>
            <ktml:modules position="user4" />
        </nav>
    </div>
</header>

<div class="container">
    <div class="row">
        <aside class="sidebar span3">
            <div class="well" style="padding: 8px 0;">
            	<ktml:modules position="left" chrome="wrapped" />
            </div>
        </aside>
        <div class="span9">
            <ktml:modules position="breadcrumb" />
            <section>               
                <ktml:variable name="content" />               
            </section>
        </div>
    </div>
</div>

<? if(@service('application')->getCfg('debug')) : ?>
    <?= @service('com://admin/debug.controller.debug')->render(); ?>
<? endif; ?>

</body>
</html>