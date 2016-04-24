<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-debugger for the canonical source repository
 */
?>

<title content="replace"><?= translate('Error').': '.$code; ?></title>

<style>
    body { margin: 15px; height: 100%;
        padding: 0px;
        font-family: Arial, Helvetica, Sans Serif;
        font-size: 11px;
        color: #333333;
        background: #ffffff;
    }

    .outline { border: 1px solid #cccccc; background: #ffffff; padding: 2px;}
    .frame {
        background-color:#FEFCF3;
        padding:8px;
        border:solid 1px #000000;
        margin-top:13px;
        margin-bottom:25px;
    }

    .table {
        border-collapse:collapse;
        margin-top:13px;
    }

    td {
        padding:3px;
        padding-left:5px;
        padding-right:5px;
        border:solid 1px #bbbbbb;
        font-size: 10px;
    }

    .type {
        background-color:#cc0000;
        color:#ffffff;
        font-weight:bold;
        padding:3px;
    }
</style>

<table width="550" align="center" class="outline">
    <tr>
        <td align="center">
            <h1><?= $code ?> - <?= translate('An error has occurred') ?></h1>
        </td>
    </tr>
    <tr>
        <td width="39%" align="center">
            <p><?= $message ?></p>
            <p>
                <? if(count($trace)) : ?>
                <?= import('default_backtrace.html'); ?>
                <? endif; ?>
            </p>
        </td>
    </tr>
</table>
