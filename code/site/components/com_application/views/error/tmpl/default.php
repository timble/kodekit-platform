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

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">
<head>
    <link rel="stylesheet" href="media://com_application/css/error.css" type="text/css" />
    <title><?= @text('Error').': '.$error->getCode(); ?></title>
</head>
<body>
<div align="center">
    <div id="outline">
        <div id="errorboxoutline">
            <div id="errorboxheader">
            <? if(KDEBUG) : ?>
                <?= (string) $error ?>
            <? else : ?>
                <?= KHttpResponse::getMessage($error->getCode()) ?>
            <? endif ?>
            </div>
            <div id="errorboxbody">
                <p><strong><?= @text('You may not be able to visit this page because of:'); ?></strong></p>
                <ol>
                    <li><?= @text('An out-of-date bookmark/favourite'); ?></li>
                    <li><?= @text('A search engine that has an out-of-date listing for this site'); ?></li>
                    <li><?= @text('A mis-typed address'); ?></li>
                    <li><?= @text('You have no access to this page'); ?></li>
                    <li><?= @text('The requested resource was not found'); ?></li>
                    <li><?= @text('An error has occurred while processing your request.'); ?></li>
                </ol>
                <p><strong><?= @text('Please try one of the following pages:'); ?></strong></p>
                <p>
                <ul>
                    <li><a href="<? KRequest::root(); ?>" title="<?= @text('Go to the home page'); ?>"><?= @text('Home Page'); ?></a></li>
                </ul>
                </p>
                <p><?= @text('If difficulties persist, please contact the system administrator of this site.'); ?></p>
                <div id="techinfo">
                    <p><?= $error->getMessage(); ?></p>
                    <p>
                        <? if(KDEBUG) : ?>
                        <?= @template('default_backtrace'); ?>
                        <? endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>