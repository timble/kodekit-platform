<?php
defined('KOOWA') or die( 'Restricted access' );

echo @helper('behavior.tooltip');
?>

<script src="media://system/js/mootools.js" />  
<script src="media://lib_koowa/js/koowa.js" />

<form action="<?= @route() ?>" method="get" name="adminForm">
        <input type="hidden" name="scope" value="<?= $state->scope;?>" />

	<table class="adminlist">
		<thead>
			<tr>
				<th width="10">
					
				</th>
				<th class="title">
					<?= @helper('grid.sort',  array('column' => 'title')   ); ?>
				</th>
				<th width="5%">
					<?= @helper('grid.sort',  array('column' => 'published')   ); ?>
				</th>
				<th width="8%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Order', 'column' => 'ordering')   ); ?>
				</th>
				<th width="10%">
					<?= @helper('grid.sort',  array('title' => 'Access', 'column' => 'groupname')   ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Categories', 'column' => 'categorycount') ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Active', 'column' => 'activecount') ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Trash', 'column' => 'trashcount') ); ?>
				</th>
			</tr>
			<tr>
				<td align="center">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count( $rows );?>);" />
				</td>
				<td>
					<?= @template('admin::com.default.view.list.search_form') ?>
				</td>
				<td align="center"> 
					<?= @helper('listbox.published', array('name' => 'published', 'attribs' => array('onchange' => 'this.form.submit();'))); ?>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?= @helper('paginator.pagination', array('total' => $total)); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?
		$i = 0;
		foreach ( $sections as $section ) {  ?>
			<tr>
				<td align="center">
					<?= @helper( 'grid.checkbox' , array('row' => $section)); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?= @text( 'Description' ).'::'. htmlspecialchars($section->description); ?>">
					<a href="<?= @route( 'index.php?&option=com_sections&view=section&id='.$section->id ); ?>">
<?= htmlspecialchars($section->title); ?></a>
					</span>
				</td>
				<td align="center">
					<?= @helper( 'admin::com.sections.template.helper.grid.publish' , array('row' => $section)); ?>
				</td>
				<td class="order">
					<?= @helper( 'grid.order' , array('row' => $section)); ?>
				</td>
				<td align="center">
					<?= @helper('grid.access', array( 'row' => $section )) ;?>
				</td>
				<td align="center">
					<?= $section->categorycount; ?>
				</td>
				<td align="center">
					<?= $section->activecount; ?>
				</td>
				<td align="center">
					<?= $section->trashcount; ?>
				</td>
			</tr>
			<?
			$i++;
		}
		?>
		</tbody>		
		</table>
		</form>		
