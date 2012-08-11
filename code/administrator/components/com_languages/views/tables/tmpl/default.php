<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.grid.toolbar') ?>
<?= @template('default_sidebar') ?>

<form id="tables-form" action="" method="post" class="-koowa-grid">
    <?= @template('default_filter') ?>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="10">
				    <?= @helper('grid.checkall') ?>
				</th>
				<th width="20%">
				    <? if($state->translated !== false) : ?>
					    <?= @helper('grid.sort', array('column' => 'table_name', 'title' => 'Name')) ?>
					<? else : ?>
					    <?= @text('Name') ?>
					<? endif ?>
				</th>
				<th>
					<?= @text('Description') ?>
				</th>
				<? if($state->translated !== false) : ?>
    				<th width="15%" nowrap="nowrap">
    					<?= @helper('grid.sort', array('column' => 'enabled', 'title' => 'Published')) ?>
    				</th>
    				
    				<? if(KDEBUG): ?>
    					<th width="15%" nowrap="nowrap">
    						<?= @text('Unique Column') ?>
    					</th>
    	                <th width="15%" nowrap="nowrap">
    	                	<?= @text('Title Column') ?>
    	                </th>
    				<? endif ?>
				<? else : ?>
				    <th width="15%" nowrap="nowrap">
				        <?= @text('Text') ?>
				    </th>
				<? endif ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					 <?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<? foreach($tables as $table) : ?>
			<tr>
				<td align="center">
					<?= @helper('grid.checkbox', array('row' => $table, 'column' => 'table_name')) ?>
				</td>
				<td>
					<?= KInflector::humanize($table->table_name) ?>
				</td>
				<td>
					<?= @text($table->description) ?>
				</td>
				<? if($state->translated !== false) : ?>
    				<td align="center">
    					<?= @helper('grid.enable', array('row' => $table)) ?>   
    				</td>
    				
    				<? if(KDEBUG): ?>
    					<td align="center">
    						<?= $table->unique_column ?>
    					</td>
    	                <td align="center">
    	                    <?= $table->title_column ?>
    	                </td>
                    <? endif ?>
                <? else : ?>
                    <td align="center">
                        <? if($table->title_column) : ?>
                            <img src="media://lib_koowa/images/enabled.png" border="0" alt="Yes" title="Yes">
                        <? else : ?>
                            <img src="media://lib_koowa/images/disabled.png" border="0" alt="No" title="No">
                        <? endif ?>
                    </td>
                <? endif ?>
			</tr>
			<? endforeach ?>
		</tbody>
	</table>
</form>