<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script src="media://lib_koowa/js/koowa.js" />

<form action="<?= @route('id='.$user->id) ?>" method="post" name="adminForm">
	<div class="grid_8">
		<fieldset class="adminform">
			<legend><?= @text('User Details') ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" class="key">
						<?= @text('Name') ?>:
					</td>
					<td>
						<input type="text" name="name" value="<?= $user->name ?>" size="40" />
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('Username') ?>:
					</td>
					<td>
						<input type="text" name="username" value="<?= $user->username ?>" maxlength="150" size="40" />
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('E-Mail') ?>:
					</td>
					<td>
						<input type="text" name="email" value="<?= $user->email ?>" size="40" />
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('New Password') ?>:
					</td>
					<td>
						<input type="password" name="password" maxlength="100" size="40" />
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('Verify Password') ?>:
					</td>
					<td>
						<input type="password" name="password_verify" maxlength="100" size="40" />
					</td>
				</tr>
				<tr>
                    <td width="100" class="key">
                        <?= @text('Group') ?>:
                    </td>
                    <td>
                       <?= @helper('listbox.groups', array('selected' => $user->users_group_id)) ?>
                    </td>
                </tr>
			</table>

			<?
				$user_instance = $user->id ? JUser::getInstance($user->id) : JUser::getInstance();
				echo $user_instance->getParameters(true)->render('params');
			?>
		</fieldset>
	</div>
	<div class="grid_4">
		<div class="panel">
			<h3><?= @text('System Information') ?></h3>
			<table class="admintable">
				<tr>
					<td width="100" class="key">
						<?= @text('Enable User') ?>:
					</td>
					<td>
						<?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $user->enabled)) ?>
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('Receive System E-mails') ?>:
					</td>
					<td>
						<?= @helper('select.booleanlist', array('name' => 'send_email', 'selected' => $user->send_email)) ?>
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('Register Date') ?>:
					</td>
					<td>
						<?= @helper('date.format', array('date' => $user->registered_on, 'format' => '%Y-%m-%d %H:%M:%S')) ?>
					</td>
				</tr>
				<tr>
					<td width="100" class="key">
						<?= @text('Last Visit') ?>:
					</td>
					<td>
						<? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
							<?= @text('Never') ?>
						<? else : ?>
							<?= $user->last_visited_on ?>
						<? endif ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>