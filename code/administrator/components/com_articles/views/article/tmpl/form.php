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
<script src="media://com_articles/js/article.js" />

<script>
    var categories = <?= json_encode(KFactory::tmp('admin::com.articles.model.categories')->getList()) ?>;

    <? if($article->category_id) : ?>
        window.addEvent('domready', function() {
            document.id('article-form-categories').set('value', <?= $article->category_id ?>);
        });
    <? endif ?>

    if(Form && Form.Validator) {
        Form.Validator.add('validate-unsigned', {
            errorMsg: Form.Validator.getMsg("required"),
            test: function(field){
                return field.get('value').toInt() >= 0;
            }
        });
    }
</script>

<form action="<?= @route('id='.$article->id) ?>" method="post" id="article-form" class="-koowa-form">
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
            </table>
        </div>
        <div class="panel">
            <h3><?= @text('Parameters') ?></h3>
            <table width="100%" class="paramlist admintable" cellspacing="1">
                <tbody>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="created_by"><?= @text('Author') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <?= @helper('admin::com.users.template.helper.listbox.users',
                                array('selected' => $article->id ? $article->created_by : $user->id, 'deselect' => false, 'name' => 'created_by')) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="created_by_alias"><?= @text('Author Alias') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <input type="text" name="created_by_alias" value="<?= $article->created_by_alias ?>" size="25">
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="access"><?= @text('Access Level') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <?= @helper('listbox.access', array('selected' => $article->access, 'deselect' => false)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="created_on"><?= @text('Created Date') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <? $options = array('format' => '%Y-%m-%d %H:%M:%S') ?>

                            <? if($article->id) $options['date'] = $article->created_on ?>
                            <?= JHTML::_('calendar', @helper('date.format', $options), 'created_on', 'created_on', $format = '%Y-%m-%d %H:%M:%S',
                                array('class' => 'inputbox', 'size' => 25, 'maxlength' => '19')) ?>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="publish_up"><?= @text('Start Publishing') ?></label>
                        </td>
                        <td class="paramlist_value">
                            <? if($article->id) $options['date'] = $article->publish_up ?>
                            <?= JHTML::_('calendar', @helper('date.format', $options), 'publish_up', 'publish_up', $format = '%Y-%m-%d %H:%M:%S',
                                array('class' => 'inputbox', 'size' => 25, 'maxlength' => '19')) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label for="publish_down"><?= @text('Finish Publishing') ?></label>
                        </td>
                        <td class="paramlist_value">
                        <? $date = !(int) $article->publish_down ? JText::_('Never') : @helper('date.format',
                            array('date' => $article->publish_down, 'format' => '%Y-%m-%d %H:%M:%S')) ?>
                        <?= JHTML::_('calendar', $date, 'publish_down', 'publish_down', $format = '%Y-%m-%d %H:%M:%S',
                            array('class' => 'inputbox', 'size' => 25, 'maxlength' => '19')) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel folders group">
            <h3><?= @text('Category') ?></h3>
            <?= @template('form_categories', array('categories' =>  KFactory::tmp('admin::com.articles.model.categories')->getList(), 'article' => $article)) ?>
        </div>
        <div class="panel">
            <h3><?= @text('Description') ?></h3>
            <table width="100%" class="paramlist admintable" cellspacing="1">
    		<tbody>
        		<tr>
            		<td class="paramlist_value">
                		<textarea name="meta_description" cols="58" rows="5" class="text_area"><?= $article->meta_description ?></textarea>
            		</td>
        		</tr>
    		</tbody>
			</table>
        </div>
    </div>
</form>