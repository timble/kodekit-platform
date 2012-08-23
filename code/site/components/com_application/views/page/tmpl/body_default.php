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

<header class="navbar navbar-fixed-top">
    <div class="navbar-inner container">
        <nav>
            <ktml:modules position="user3" />
        </nav>
    </div>
</header>

<div style="padding-top: 60px;" class="container">
    <div class="row">
        <aside class="sidebar span3">
            <ktml:modules position="left" chrome="wrapped" />
        </aside>
        <div class="span9">
            <ktml:modules position="breadcrumb" />
            <section>
                <ktml:variable name="component" />
            </section>
        </div>
    </div>
</div>

<? if(KDEBUG) : ?>
    <?= @service('com://admin/debug.controller.debug')->display(); ?>
<? endif; ?>

</body>
</html>