<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_installer/css/installer.css" />
<script src="media://com_installer/js/installer.js" />

<? if(isset($message)) echo $message ?>

<form action="" method="post" enctype="multipart/form-data" class="-installer-form">
    <ul>
        <li><?= @text('Install by:') ?></li>
        <li class="install-by-upload"><a href="#install_package"><?= @text('File') ?></a></li>
        <li class="install-by-directory"><a href="#install_directory"><?= @text('Directory') ?></a></li>
        <li class="install-by-url"><a href="#install_url"><?= @text('URL') ?></a></li>
    </ul>
    <div id="install_package" class="install-by">
        <input class="install-input-file" id="package" name="package" type="file" accept="application/x-gzip, application/x-tar, application/x-bzip2, application/zip" />
        <div class="install-by-file-notice"><?= @text('Select an installable file on your computer') ?></div>
        <input class="install-reset" type="reset" value="<?= @text('Cancel') ?>" />
        <input class="install-submit" type="submit" value="<?= @text('Upload File') ?> &amp; <?= @text('Install') ?>" />
    </div>
    <div id="install_directory" class="install-by">
        <input type="text" id="directory" name="directory" class="input_box" value="<?= $state->directory ?>" placeholder="<?= $state->directory ?>" data-root="<?= JPATH_ROOT ?>" />
    </div>
    <div id="install_url" class="install-by">
        <input type="text" id="url" name="url" class="input_box" value="<?= $state->url ?>" placeholder="<?= $state->url ?>" />
    </div>
</form>