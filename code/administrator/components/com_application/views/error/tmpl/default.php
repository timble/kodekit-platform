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
<table width="550" align="center" class="outline">
    <tr>
        <td align="center">
            <h1>
                <?= $error->getCode() ?> - <?= @text('An error has occurred') ?>
        </td>
    </tr>
    <tr>
        <td width="39%" align="center">
            <? if(ini_get('display_errors')) : ?>
            <p><?= (string) $error ?></p>
            <? else : ?>
            <p><?= KHttpResponse::getMessage($error->getCode()) ?></p>
            <? endif ?>
            <p>
                <? if(ini_get('display_errors')) : ?>
                <?= @template('default_backtrace'); ?>
                <? endif; ?>
            </p>
        </td>
    </tr>
</table>
</body>
</html>