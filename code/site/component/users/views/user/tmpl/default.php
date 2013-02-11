<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="page-header">
    <h1 class="page-header"><?= @escape($parameters->get('page_title')) ?></h1>
</div>

<p><?= nl2br(@escape($parameters->get('welcome_desc', @text('WELCOME_DESC')))) ?></p>