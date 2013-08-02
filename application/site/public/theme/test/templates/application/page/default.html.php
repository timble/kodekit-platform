<?
/**
 * Belgian Police Web Platform - Police Component
 *
 * @copyright   Copyright (C) 2012 - 2013 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.police.be
 */
?>
<? $columns = @helper('module.count', array('condition' => 'left')) ? '9' : '12' ?>
<? $site = @escape(@object('application')->getCfg('site' )) ?>

<? if($extension == 'police') { $columns = '12'; } ?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">

<?= @template('page_head.html') ?>
<body>
<div id="wrap" class="container-fluid">
    <div class="container-header">
        <div class="row-fluid">
            <div class="span3">
                <div class="logo" itemscope itemtype="http://schema.org/Organization">
                    <a itemprop="url" href="/<?= $site ?>">
                        <img itemprop="logo" src="media://application/images/logo-nl.png" />
                        
                    </a>
                </div>
            </div>
            <div class="span9">
                <span class="slogan hidden-phone">Bel <strong>101</strong> voor dringende politiehulp. Geen spoed, wél politie? Bel <strong></strong></span>
                <div class="navbar navbar-responsive">
                <a class="navbar__logo" href="/<?= $site ?>">
                    <img src="media://application/images/logo-flame.png" />
                    
                </a>
                <div class="navbar-inner">
                    <ktml:modules position="navigation">
                        <ktml:modules:content />
                    </ktml:modules>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-banner banner5388">
        <img src="media://application/images/banners/<?= $site ?>.jpg" />
    </div>

    <div class="container-breadcrumb">
        <ktml:modules position="breadcrumbs">
            <ktml:modules:content />
        </ktml:modules>
    </div>

    <div class="container-content <?= $extension ?>">
        <div class="row-fluid">
            <ktml:modules position="left">
                <div class="span3 sidebar hidden-phone">
                    <ktml:modules:content />
                </div>
            </ktml:modules>
            <div class="span<?= $columns ?> component">
                <ktml:content />
            </div>
        </div>
    </div>

    <div class="container-media">
        <div class="row-fluid hidden-phone">
            <div class="span6">
                <h3><?= @text('Laatste nieuws') ?></h3>
                
            </div>
            <div class="span3">
                <h3 style="padding-left: 12px" ><?= @text('Meer weten') ?></h3>
                <ktml:modules position="footermenu">
                    <ktml:modules:content />
                </ktml:modules>
            </div>
            <div class="span3">
                <h3>Mijn wijkinspecteur</h3>
            </div>
        </div>
    </div>
    <div id="push"></div>
</div>

<div class="container-fluid container-copyright">
    <div class="row-fluid">
        <div class="span6">
            <p>
                <a href="http://www.twitter.com/politieleuven"><i class="icon-twitter"></i> Twitter</a> | <a href="http://www.facebook.com/politieleuven"><i class="icon-facebook"></i> Facebook</a>
            </p>
        </div>
        <div class="span6 copyright">
            <p>

                <a style="margin-left: 10px" target="_blank" href="http://www.lokalepolitie.be/portal/nl/disclaimer.html">Disclaimer</a> -
                <a target="_blank" href="http://www.lokalepolitie.be/portal/nl/privacy.html">Privacy</a>
                <a style="margin-left: 10px" target="_blank" href="http://www.belgium.be"><image src="media://application/images/icon_belgium.gif" /></a>
            </p>
        </div>
    </div>
</div>

</body>
</html>