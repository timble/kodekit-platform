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
<head>
    <title><?= translate('Error').': '.$code; ?></title>

    <link href="assets://application/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <style src="assets://application/stylesheets/default.css" />

    <ktml:style>
</head>

<body>
    <div class="container">
        <h1><?= translate('Error').': '.$code; ?></h1>
        <? if(count($trace)) : ?>
            <?= import('default_backtrace.html'); ?>
        <? endif; ?>
    </div>
</body>
</html>