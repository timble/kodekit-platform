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

<?= @helper('behavior.validator') ?>
<? /* @TODO move this into a separate JS file */ ?>
<script>
if(Form && Form.Validator) {
    Form.Validator.add('validate-match', {
		errorMsg: function(element, props){
			return Form.Validator.getMsg('match').substitute({matchName: props.matchName || document.id(props.matchInput).get('name')});
		},
		test: function(element, props){
			var eleVal = element.get('value');
			var matchVal = document.id(props.matchInput) && document.id(props.matchInput).get('value');
			return matchVal ? eleVal == matchVal : true;
		}
	});
}
</script>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route('id='.$user->id) ?>" method="post" class="-koowa-form">
	<div class="grid_8">
		<fieldset class="adminform">
			<legend><?= @text('User Details') ?></legend>
			<table class="admintable">
				<tr>
					<td class="key">
						<label for="name">
						    <?= @text('Name') ?>:
						</label>
					</td>
					<td>
						<input class="required" type="text" name="name" value="<?= $user->name ?>" size="40" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="username">
						    <?= @text('Username') ?>:
						</label>
					</td>
					<td>
						<input class="required minLength:2" type="text" name="username" value="<?= $user->username ?>" maxlength="150" size="40" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="email">
						    <?= @text('E-Mail') ?>:
						</label>
					</td>
					<td>
						<input class="required validate-email" type="text" name="email" value="<?= $user->email ?>" size="40" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="password">
						    <?= @text('New Password') ?>:
						</label>
					</td>
					<td>
						<input id="password" type="password" name="password" maxlength="100" size="40" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="password_verify">
						    <?= @text('Verify Password') ?>:
						</label>
					</td>
					<td>
						<input class="validate-match matchInput:'password' matchName:'password'" type="password" name="password_verify" maxlength="100" size="40" />
					</td>
				</tr>
				<tr>
                    <td class="key">
                        <label for="users_group_id">
                            <?= @text('Group') ?>:
                        </label>
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
					<td class="key">
						<label for="enabled">
						    <?= @text('Enable User') ?>:
						</label>
					</td>
					<td>
						<?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $user->enabled)) ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="send_email">
						    <?= @text('Receive System E-mails') ?>:
						</label>
					</td>
					<td>
						<?= @helper('select.booleanlist', array('name' => 'send_email', 'selected' => $user->send_email)) ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?= @text('Register Date') ?>:
					</td>
					<td>
						<? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
							<?= @text('Never') ?>
						<? else : ?>
							<?= @helper('date.format', array('date' => $user->registered_on, 'format' => '%Y-%m-%d %H:%M:%S')) ?>
						<? endif ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?= @text('Last Visit') ?>:
					</td>
					<td>
						<? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
							<?= @text('Never') ?>
						<? else : ?>
							<?= @helper('date.format', array('date' => $user->last_visited_on, 'format' => '%Y-%m-%d %H:%M:%S')) ?>
						<? endif ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>