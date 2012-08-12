<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<?= @helper('behavior.mootools'); ?>
<?= @helper('behavior.keepalive'); ?>

<style src="media://com_articles/css/toolbar.css"/>
<style src="media://com_articles/css/site.css"/>

<script src="media://lib_koowa/js/koowa.js"/>

<div id="toolbar-box">
    <?= @helper('com://admin/default.template.helper.toolbar.render', array('toolbar' => $toolbar));?>
</div>

<div class="clear_both"></div>

<? if ($params->get('show_page_title')) : ?>
<h1>
    <?= @escape($params->get('page_title')); ?>
</h1>
<? endif; ?>

<script type="text/javascript">
    window.addEvent('domready', function () {

        var section_select = $('section_id');
        var category_select = $('category_id');

        section_select.addEvent('change', function (event, category_id) {

            var url = "<?= @route('view=categories&format=json');?>";
            var section_id = this.get('value').toInt();

            if (section_id <= 0) {
                section_id = '';
            }

            url += '&section=' + section_id;

            category_select.empty();

            new Request({
                method:'get',
                url:url,
                onSuccess:function (response) {
                    var categories = JSON.decode(response);
                    categories.items.unshift({data:{title:"- Select -", id:-1}}, {data:{title:"<?= @text('Uncategorized');?>", id:0}})
                    Array.each(categories.items, function (category) {
                        data = category.data;
                        new Element('option')
                            .set('text', data.title)
                            .set('value', data.id)
                            .inject(category_select);
                        // Set the value if available
                        if (category_id) {
                            category_select.set('value', category_id)
                        }
                    });
                }
            }).send();
        });
        // Initialize the category selector if section is already set.
        var section_id = section_select.get('value').toInt();
        if (section_id > 0) {
            var category_id = category_select.get('value').toInt();
            section_select.fireEvent('change', [null, category_id]);
        }
    });
</script>

<form method="post" action="" class="-koowa-form">
    <fieldset>
        <legend><? echo JText::_('Editor'); ?></legend>
        <table width="100%">
            <tr>
                <td>
                    <label for="title">
                        <? echo JText::_('Title'); ?>:
                    </label>
                    <input class="inputbox" type="text" id="title" name="title" size="50" maxlength="100"
                           value="<? echo @escape($article->title); ?>"/>
                </td>
            </tr>
        </table>
        <?= @service('com://admin/editors.controller.editor')->name('text')->data($article->text)->display(); ?>
    </fieldset>
    <fieldset>
        <legend><?= @text('Publishing'); ?></legend>
        <table>
            <tr>
                <td class="key">
                    <label for="section_id">
                        <?= @text('Section'); ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('listbox.sections', array(
                            'name'     => 'section_id',
                            'selected' => $article->section_id,
                            'attribs'  => array('id' => 'section_id'))
                    ); ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="category_id">
                        <?= @text('Category'); ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('listbox.categories', array(
                        'selected' => $article->category_id,
                        'name'     => 'category_id',
                        'attribs'  => array('id' => 'category_id')));
                    ?>
                </td>
            </tr>
            <? if ($article->editable) : ?>
            <tr>
                <td class="key">
                    <label for="state">
                        <?= @text('Published'); ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('select.booleanlist', array(
                        'name'     => 'state',
                        'selected' => $article->state)
                    ); ?>
                </td>
            </tr>
            <? endif; ?>
            <tr>
                <td width="120" class="key">
                    <label for="featured">
                        <?= @text('Featured'); ?>:
                    </label>
                </td>
                <td>
                    <? echo @helper('select.booleanlist', array(
                        'name'     => 'featured',
                        'selected' => $article->featured)
                    ); ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="created_by_alias">
                        <?= @text('Author Alias'); ?>:
                    </label>
                </td>
                <td>
                    <input type="text" id="created_by_alias" name="created_by_alias" size="50" maxlength="100"
                           value="<?= @escape($article->created_by_alias); ?>" class="inputbox"/>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="publish_up">
                        <?= @text('Start Publishing'); ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('form.publish', array('row' => $article));?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="publish_down">
                        <?= @text('Finish Publishing'); ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('form.unpublish', array('row' => $article)); ?>
                </td>
            </tr>
            <tr>
                <td valign="top" class="key">
                    <label for="access">
                        <?= @text('Access Level'); ?>:
                    </label>
                </td>
                <td>
                    <?= JHTML::_('list.accesslevel', $article); ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <label for="ordering">
                        <?= @text('Ordering'); ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('listbox.ordering', array('row' => $article)); ?>
                </td>
            </tr>
        </table>
    </fieldset>
</form>