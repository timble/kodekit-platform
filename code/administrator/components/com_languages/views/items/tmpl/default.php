<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.grid.toolbar') ?>

<ktml:module position="sidebar">
    <?= @template('default_sidebar'); ?>
</ktml:module>

<form action="" method="post" class="-koowa-grid">
    <!-- <table class="adminlist">
        <tr>
            <td align="left" width="100%">
                <?= @text('SEARCH'); ?>
                <input id="search" name="filter" value="<?//= @$filters['filter']; ?>" />
                <button onclick="this.form.submit();"><?= @text('SEARCH'); ?></button>
                <button onclick="document.getElementById('search').value='';this.form.submit();"><?= @text('RESET'); ?></button>
            </td>
            <td nowrap="nowrap">
                <? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                <?//= @helper('select.tables',     @$filters['table_name'], 'filter_table_name', $attribs, 'table_name', true); ?>
                <?//= @helper('select.languages',  @$filters['iso_code'],   'filter_iso_code',   $attribs, 'iso_code', true); ?>
                <?//= @helper('select.statuses',   @$filters['status'],     'filter_status',     $attribs, 'status', true); ?>
                <?//= @helper('select.translators',@$filters['translator'], 'filter_translator', $attribs, 'translator', true); ?>
            </td>
        </tr>
    </table> -->

    <table>
        <thead>
            <tr>
                <th width="20">
                    <?= @helper('grid.checkall') ?>
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'table')) ?>
                </th>
                <th width="65">
                    <?= @helper('grid.sort', array('column' => 'iso_code', 'title' => 'Flag')) ?>
                </th>
                <th class="title">
                    <?= @helper('grid.sort', array('column' => 'title')) ?>
                </th>
                <th width="85">
                    <?= @helper('grid.sort', array('column' => 'status')) ?>
                </th>
                <th width="140">
                    <?= @helper('grid.sort', array('column' => 'created_on', 'title' => 'Created')) ?>
                </th>
                <th width="140">
                    <?= @helper('grid.sort', array('column' => 'modified_on', 'title' => 'Modified')) ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="20">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?= $group_tables ? @template('default_items_group') : @template('default_items') ?>
        </tbody>
    </table>
</form>