<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://com_articles/css/article-form.css" />

<script>
    if(Form && Form.Validator) {
        Form.Validator.add('validate-unsigned', {
            errorMsg: Form.Validator.getMsg("required"),
            test: function(field){
                return field.get('value').toInt() >= 0;
            }
        });
    }
</script>

<form action="" method="post" id="article-form" class="-koowa-form">
    <div id="main" class="grid_8">
        <div class="panel title clearfix">
            <input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?= $article->title ?>" placeholder="<?= @text('Title') ?>" />
            <label for="slug"><?= @text('Slug') ?></label>
            <input class="inputbox" type="text" name="slug" id="slug" size="40" maxlength="255" value="<?= $article->slug ?>" placeholder="<?= @text('Slug') ?>" />
        </div>

        <?= @editor(array(
                'name' => 'text',
                'text' => $article->text,
                'width' => '100%',
                'height' => '300',
                'cols' => '60',
                'rows' => '20',
                'buttons' => true,
                'options' => array('theme' => 'simple', 'pagebreak', 'readmore')));
        ?>
    </div>
    <div id="panels" class="grid_4">
        <div class="panel">
            <h3><?= @text('Publish') ?></h3>
            <table class="paramlist admintable">
                <tr>
                    <td class="paramlist_key">
                        <label><?= @text('Published') ?></label>
                    </td>
                    <td>
                        <?= @helper('select.booleanlist', array('name' => 'state', 'selected' => $article->state)) ?>
                    </td>
                </tr>
                <tr>
                    <td class="paramlist_key">
                        <label><?= @text('Featured') ?></label>
                    </td>
                    <td>
                        <?= @helper('select.booleanlist', array('name' => 'featured', 'selected' => $article->featured)) ?>
                    </td>
                </tr>
                <tr>
                	<td width="40%" class="paramlist_key">
            	        <label for="publish_up"><?= @text('Publish on') ?></label>
                    </td>
                    <td class="paramlist_value">
                        <?= @helper('behavior.calendar', array('date' => $article->publish_up, 'name' => 'publish_up')); ?>
                    </td>
                </tr>
                <tr>
                	<td width="40%" class="paramlist_key">
                    	<label for="publish_down"><?= @text('Unpublish on') ?></label>
                    </td>
                    <td class="paramlist_value">
                        <?= @helper('behavior.calendar', array('date' => $article->publish_down, 'name' => 'publish_down')); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel">
            <h3><?= @text('Details') ?></h3>
            <table width="100%" class="paramlist admintable" cellspacing="1">
                <tbody>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="created_by"><?= @text('Author') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <?= @helper('com://admin/users.template.helper.autocomplete.users', array('column' => 'created_by', 'value' => $article->id ? $article->created_by : $user->id)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="access"><?= @text('Visibility') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <?= @helper('listbox.access', array('selected' => $article->access, 'deselect' => false)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="created_on"><?= @text('Created on') ?></label>
                        </td>
                        <td class="paramlist_value">
                        	<?= @helper('behavior.calendar', array('date' => $article->created_on, 'name' => 'created_on')); ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel folders group">
            <h3><?= @text('Category') ?></h3>
            <?= @template('form_categories', array('categories' =>  @service('com://admin/articles.model.categories')->getList(), 'article' => $article)) ?>
        </div>
        <div class="panel">
            <h3><?= @text('Description') ?></h3>
            <table width="100%" class="paramlist admintable" cellspacing="1">
    		<tbody>
        		<tr>
            		<td class="paramlist_value">
                		<textarea name="description" cols="58" rows="5" class="text_area"><?= $article->description ?></textarea>
            		</td>
        		</tr>
    		</tbody>
			</table>
        </div>
    </div>
</form>