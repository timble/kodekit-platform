<?php
/**
* @version		$Id: admin.content.html.php 17299 2010-05-27 16:06:54Z ian $
* @package		Joomla
* @subpackage	Content
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * HTML View class for the Content component
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.0
 */
class ContentView
{
	/**
	* Writes a list of the articles
	* @param array An array of article objects
	*/
	function showContent( &$rows, &$lists, $page, $redirect )
	{

		global $mainframe;

		// Initialize variables
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$config	=& JFactory::getConfig();
		$now	=& JFactory::getDate();

		//Ordering allowed ?
		$ordering = ($lists['order'] == 'section_name' || $lists['order'] == 'cc.title' || $lists['order'] == 'c.ordering');
		JHTML::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_content" method="post" name="adminForm">
			<table class="adminlist" cellspacing="1">
			<thead>
				<tr>
					<th width="5">
						
					</th>
					<th class="title">
						<?php echo JHTML::_('grid.sort',   'Title', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="1%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Published', 'c.state', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th nowrap="nowrap" width="1%">
						<?php echo JHTML::_('grid.sort',   'Front Page', 'frontpage', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="8%">
						<?php echo JHTML::_('grid.sort',   'Order', 'c.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
						<?php if ($ordering) echo JHTML::_('grid.order',  $rows ); ?>
					</th>
					<th width="7%">
						<?php echo JHTML::_('grid.sort',   'Access', 'groupname', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th class="title" width="8%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Section', 'section_name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th  class="title" width="8%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Category', 'cc.title', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th  class="title" width="8%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Author', 'author', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th align="center" width="10">
						<?php echo JHTML::_('grid.sort',   'Date', 'c.created', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
				</tr>
				<tr>
					<td align="center">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</td>
					<td>
						<?php echo JText::_( 'Filter' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_sectionid').value='-1';this.form.getElementById('catid').value='0';this.form.getElementById('filter_authorid').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
					<td align="center">
						<?php echo $lists['state']; ?>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<?php echo $lists['sectionid']; ?>
					</td>
					<td>
						<?php echo $lists['catid']; ?>
					</td>
					<td>
						<?php echo $lists['authorid']; ?>
					</td>
					<td></td>
				</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $page->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php
			$nullDate = $db->getNullDate();
			for ($i=0, $n=count( $rows ); $i < $n; $i++)
			{
				$row = &$rows[$i];

				$link 	= 'index.php?option=com_content&sectionid='. $redirect .'&task=edit&cid[]='. $row->id;

				$row->sect_link = JRoute::_( 'index.php?option=com_sections&task=edit&cid[]='. $row->sectionid );
				$row->cat_link 	= JRoute::_( 'index.php?option=com_categories&task=edit&cid[]='. $row->catid );

				$publish_up =& JFactory::getDate($row->publish_up);
				$publish_down =& JFactory::getDate($row->publish_down);
				$publish_up->setOffset($config->getValue('config.offset'));
				$publish_down->setOffset($config->getValue('config.offset'));
				if ( $now->toUnix() <= $publish_up->toUnix() && $row->state == 1 ) {
					$img = 'publish_y.png';
					$alt = JText::_( 'Published' );
				} else if ( ( $now->toUnix() <= $publish_down->toUnix() || $row->publish_down == $nullDate ) && $row->state == 1 ) {
					$img = 'publish_g.png';
					$alt = JText::_( 'Published' );
				} else if ( $now->toUnix() > $publish_down->toUnix() && $row->state == 1 ) {
					$img = 'publish_r.png';
					$alt = JText::_( 'Expired' );
				} else if ( $row->state == 0 ) {
					$img = 'publish_x.png';
					$alt = JText::_( 'Unpublished' );
				} else if ( $row->state == -1 ) {
					$img = 'disabled.png';
					$alt = JText::_( 'Archived' );
				}
				$times = '';
				if (isset($row->publish_up)) {
					if ($row->publish_up == $nullDate) {
						$times .= JText::_( 'Start: Always' );
					} else {
						$times .= JText::_( 'Start' ) .": ". $publish_up->toFormat();
					}
				}
				if (isset($row->publish_down)) {
					if ($row->publish_down == $nullDate) {
						$times .= "<br />". JText::_( 'Finish: No Expiry' );
					} else {
						$times .= "<br />". JText::_( 'Finish' ) .": ". $publish_down->toFormat();
					}
				}

				if ( $user->authorize( 'com_users', 'manage' ) ) {
					if ( $row->created_by_alias ) {
						$author = $row->created_by_alias;
					} else {
						$linkA 	= 'index.php?option=com_users&task=edit&cid[]='. $row->created_by;
						$author = '<a href="'. JRoute::_( $linkA ) .'" title="'. JText::_( 'Edit User' ) .'">'. $row->author .'</a>';
					}
				} else {
					if ( $row->created_by_alias ) {
						$author = $row->created_by_alias;
					} else {
						$author = $row->author;
					}
				}

				$access 	= JHTML::_('grid.access',   $row, $i, $row->state );
				$checked 	= JHTML::_('grid.checkedout',   $row, $i );
				?>
				<tr>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td>
					<?php
						if (  JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
							echo $row->title;
						} else if ($row->state == -1) {
							echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
							echo ' [ '. JText::_( 'Archived' ) .' ]';
						} else {
							?>
							<a href="<?php echo JRoute::_( $link ); ?>">
								<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?></a>
							<?php
						}
						?>
					</td>
					<?php
					if ( $times ) {
						?>
						<td align="center">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Publish Information' );?>::<?php echo $times; ?>"><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish' ?>')">
								<img src="<?php echo JURI::root(true) ?>/media/system/images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></a></span>
						</td>
						<?php
					}
					?>
					<td align="center">
						<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','toggle_frontpage')" title="<?php echo ( $row->frontpage ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>">
							<img src="<?php echo JURI::root(true) ?>/media/system/images/<?php echo ( $row->frontpage ) ? 'tick.png' : ( $row->state != -1 ? 'publish_x.png' : 'disabled.png' );?>" width="16" height="16" border="0" alt="<?php echo ( $row->frontpage ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>" /></a>
					</td>
					<td class="order">
						<span><?php echo $page->orderUpIcon( $i, ($row->catid == @$rows[$i-1]->catid), 'orderup', 'Move Up', $ordering); ?></span>
						<span><?php echo $page->orderDownIcon( $i, $n, ($row->catid == @$rows[$i+1]->catid), 'orderdown', 'Move Down', $ordering ); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
					</td>
					<td align="center">
						<?php echo $access;?>
					</td>
						<td>
							<a href="<?php echo $row->sect_link; ?>" title="<?php echo JText::_( 'Edit Section' ); ?>">
								<?php echo $row->section_name; ?></a>
						</td>
					<td>
						<a href="<?php echo $row->cat_link; ?>" title="<?php echo JText::_( 'Edit Category' ); ?>">
							<?php echo $row->name; ?></a>
					</td>
					<td>
						<?php echo $author; ?>
					</td>
					<td nowrap="nowrap">
						<?php echo JHTML::_('date',  $row->created, JText::_('DATE_FORMAT_LC4') ); ?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
			</table>

		<input type="hidden" name="option" value="com_content" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	/**
	* Writes a list of the articles
	* @param array An array of article objects
	*/
	function showArchive( &$rows, $section, &$lists, $pageNav, $option, $all=NULL, $redirect )
	{
		// Initialize variables
		$user	= &JFactory::getUser();
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == 'remove') {
				if (document.adminForm.boxchecked.value == 0) {
					alert("<?php echo JText::_( 'VALIDSELECTIONLISTSENDTRASH', true ); ?>");
				} else if ( confirm("<?php echo JText::_( 'VALIDTRASHSELECTEDITEMS', true ); ?>")) {
					submitform('remove');
				}
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php?option=com_content&amp;task=showarchive&amp;sectionid=0" method="post" name="adminForm">
		<div id="tablecell">
			<table class="adminlist">
			<thead>
			<tr>
				<th width="5">
					<?php echo JText::_( 'Num' ); ?>
				</th>
				<th width="20">
					
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',   'Title', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th width="3%"  class="title">
					<?php echo JHTML::_('grid.sort',   'ID', 'c.id', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th width="15%"  class="title">
					<?php echo JHTML::_('grid.sort',   'Section', 'sectname', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th width="15%"  class="title">
					<?php echo JHTML::_('grid.sort',   'Category', 'cc.name', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th width="15%"  class="title">
					<?php echo JHTML::_('grid.sort',   'Author', 'author', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th align="center" width="10">
					<?php echo JHTML::_('grid.sort',   'Date', 'c.created', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
			</tr>
			<tr>
				<td></td>
				<td align="center">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
				</td>
				<td>
					<?php echo JText::_( 'Filter' ); ?>:
					<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
					<input type="button" value="<?php echo JText::_( 'Go' ); ?>" class="button" onclick="this.form.submit();" />
					<input type="button" value="<?php echo JText::_( 'Reset' ); ?>" class="button" onclick="getElementById('search').value='';this.form.submit();" />
				</td>
				<td></td>
				<td>
					<?php
					if ( $all ) {
						echo $lists['sectionid'];
					} ?>
				</td>
				<td>
					<?php echo $lists['catid']; ?>
				</td>
				<td>
					<?php echo $lists['authorid']; ?>
				</td>
				<td></td>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$row->cat_link 	= JRoute::_( 'index.php?option=com_categories&task=edit&cid[]='. $row->catid );
				$row->sec_link 	= JRoute::_( 'index.php?option=com_sections&task=edit&cid[]='. $row->sectionid );

				if ( $user->authorize( 'com_users', 'manage' ) ) {
					if ( $row->created_by_alias ) {
						$author = $row->created_by_alias;
					} else {
						$linkA 	= JRoute::_( 'index.php?option=com_users&task=edit&cid[]='. $row->created_by );
						$author = '<a href="'. $linkA .'" title="'. JText::_( 'Edit User' ) .'">'. $row->author .'</a>';
					}
				} else {
					if ( $row->created_by_alias ) {
						$author = $row->created_by_alias;
					} else {
						$author = $row->author;
					}
				}

				?>
				<tr>
					<td>
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td width="20" align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
					</td>
					<td>
						<?php echo $row->title; ?>
					</td>
					<td>
						<?php echo $row->id; ?>
					</td>
					<td>
						<a href="<?php echo $row->sec_link; ?>" title="<?php echo JText::_( 'Edit Section' ); ?>">
							<?php echo $row->sectname; ?></a>
					</td>
					<td>
						<a href="<?php echo $row->cat_link; ?>" title="<?php echo JText::_( 'Edit Category' ); ?>">
							<?php echo $row->name; ?></a>
					</td>
					<td>
						<?php echo $author; ?>
					</td>
					<td nowrap="nowrap">
						<?php echo JHTML::_('date',  $row->created, JText::_( 'DATE_FORMAT_LC4' ) ); ?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
			</table>
		</div>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="sectionid" value="<?php echo $section->id;?>" />
		<input type="hidden" name="task" value="showarchive" />
		<input type="hidden" name="returntask" value="showarchive" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	/**
	* Writes the edit form for new and existing article
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param JTableContent The category object
	* @param string The html for the groups select list
	*/
	function editContent( &$row, $section, &$lists, &$sectioncategories, $option, &$form )
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		jimport('joomla.html.pane');
		JFilterOutput::objectHTMLSafe( $row );

		$db		= &JFactory::getDBO();
		$editor = &JFactory::getEditor();
        // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
		$pane	= &JPane::getInstance('sliders', array('allowAllClose' => true));

		JHTML::_('behavior.tooltip');
		?>
		<script language="javascript" type="text/javascript">
		<!--
		var sectioncategories = new Array;
		<?php
		$i = 0;
		foreach ($sectioncategories as $k=>$items) {
			foreach ($items as $v) {
				echo "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
			}
		}
		?>

		function submitbutton(pressbutton)
		{
			var form = document.adminForm;

			if ( pressbutton == 'menulink' ) {
				if ( form.menuselect.value == "" ) {
					alert( "<?php echo JText::_( 'Please select a Menu', true ); ?>" );
					return;
				} else if ( form.link_name.value == "" ) {
					alert( "<?php echo JText::_( 'Please enter a Name for this menu item', true ); ?>" );
					return;
				}
			}

			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			var text = <?php echo $editor->getContent( 'text' ); ?>
			if (form.title.value == ""){
				alert( "<?php echo JText::_( 'Article must have a title', true ); ?>" );
			} else if (form.sectionid.value == "-1"){
				alert( "<?php echo JText::_( 'You must select a Section', true ); ?>" );
			} else if (form.catid.value == "-1"){
				alert( "<?php echo JText::_( 'You must select a Category', true ); ?>" );
 			} else if (form.catid.value == ""){
 				alert( "<?php echo JText::_( 'You must select a Category', true ); ?>" );
			} else if (text == ""){
				alert( "<?php echo JText::_( 'Article must have some text', true ); ?>" );
			} else {
				<?php
				echo $editor->save( 'text' );
				?>
				submitform( pressbutton );
			}
		}
		//-->
		</script>

		<form action="index.php" method="post" name="adminForm" class="com_content">

		<div class="grid_8">
				<div class="border-radius-4 title clearfix">
					<input class="inputbox border-radius-4" type="text" name="title" id="title" size="40" maxlength="255" value="<?php echo $row->title; ?>" placeholder="<?php echo JText::_( 'Title' ); ?>" />
				
					<label for="alias">
						<?php echo JText::_( 'Alias' ); ?>
						<input class="inputbox border-radius-4" type="text" name="alias" id="alias" size="40" maxlength="255" value="<?php echo $row->alias; ?>" title="<?php echo JText::_( 'ALIASTIP' ); ?>" placeholder="<?php echo JText::_( 'Alias' ); ?>" />
					</label>
				</div>
				
				<?php
				// parameters : areaname, content, width, height, cols, rows
				echo $editor->display( 'text',  $row->text , '100%', '550', '75', '20' ) ;
				?>	
			</div>
			<div class="grid_4">
	
			<div class="panel">
				<h3><?php echo JText::_( 'Publish' ); ?></h3>
				<table class="paramlist admintable">
				<tr>
					<td class="paramlist_key">
						<label>
							<?php echo JText::_( 'Published' ); ?>
						</label>
					</td>
					<td>
						<?php echo $lists['state']; ?>
					</td>
				</tr>
				<tr>
					<td class="paramlist_key">
						<label>
						<?php echo JText::_( 'Frontpage' ); ?>
						</label>
					</td>
					<td>
						<?php echo $lists['frontpage']; ?>
					</td>
				</tr>
				<tr>
					<td class="paramlist_key">
						<label for="sectionid">
							<?php echo JText::_( 'Section' ); ?>
						</label>
					</td>
					<td>
						<?php echo $lists['sectionid']; ?>
					</td>
				</tr>
				<tr>
					<td class="paramlist_key">
						<label for="catid">
							<?php echo JText::_( 'Category' ); ?>
						</label>
					</td>
					<td>
						<?php echo $lists['catid']; ?>
					</td>
				</tr>
				</table>
			</div>
				<div class="panel">
					<h3><?php echo JText::_( 'Parameters' ); ?></h3>
					<?php echo $form->render('details'); ?>
				</div>
				<div class="panel">
					<h3><?php echo JText::_( 'Metadata' ); ?></h3>
					<?php echo $form->render('meta', 'metadata'); ?>
				</div>
			</div>

		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="version" value="<?php echo $row->version; ?>" />
		<input type="hidden" name="mask" value="0" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
		echo JHTML::_('behavior.keepalive');
	}


	/**
	* Form to select Section/Category to move item(s) to
	* @param array An array of selected objects
	* @param int The current section we are looking at
	* @param array The list of sections and categories to move to
	*/
	function moveSection( $cid, $sectCatList, $option, $sectionid, $items )
	{
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if (!getSelectedValue( 'adminForm', 'sectcat' )) {
				alert( "<?php echo JText::_( 'Please select something', true ); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>

		<form action="index.php" method="post" name="adminForm">

		<table class="adminform">
		<tr>
			<td  valign="top" width="40%">
			<strong><?php echo JText::_( 'Move to Section/Category' ); ?>:</strong>
			<br />
			<?php echo $sectCatList; ?>
			<br /><br />
			</td>
			<td  valign="top">
			<strong><?php echo JText::_( 'Articles being Moved' ); ?>:</strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->title ."</li>";
			}
			echo "</ol>";
			?>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="sectionid" value="<?php echo $sectionid; ?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach ($cid as $id) {
			echo "\n<input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	/**
	* Form to select Section/Category to copys item(s) to
	*/
	function copySection( $option, $cid, $sectCatList, $sectionid, $items  )
	{
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if (!getSelectedValue( 'adminForm', 'sectcat' )) {
				alert( "<?php echo JText::_( 'VALIDSELECTSECTCATCOPYITEMS', true ); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">

		<table class="adminform">
		<tr>
			<td  valign="top" width="40%">
			<strong><?php echo JText::_( 'Copy to Section/Category' ); ?>:</strong>
			<br />
			<?php echo $sectCatList; ?>
			<br /><br />
			</td>
			<td  valign="top">
			<strong><?php echo JText::_( 'Articles being copied' ); ?>:</strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->title ."</li>";
			}
			echo "</ol>";
			?>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="sectionid" value="<?php echo $sectionid; ?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach ($cid as $id) {
			echo "\n<input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	function previewContent()
	{
		global $mainframe;

		$editor		=& JFactory::getEditor();

		$document	=& JFactory::getDocument();
		$document->setLink(JURI::root());

		JHTML::_('behavior.caption');

		?>
		<script>
		var form = window.top.document.adminForm
		var title = form.title.value;

		var alltext = window.top.<?php echo $editor->getContent('text') ?>;
		alltext = alltext.replace(/<hr\s+id=(\"|')system-readmore(\"|')\s*\/*>/i, '');

		</script>

		<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0">
			<tr>
				<td class="contentheading" colspan="2"><script>document.write(title);</script></td>
			</tr>
		<tr>
			<script>document.write("<td valign=\"top\" height=\"90%\" colspan=\"2\">" + alltext + "</td>");</script>
		</tr>
		</table>
		<?php
	}

	/**
	* Renders pagebreak options
	*
	*/
	function insertPagebreak()
	{
		$eName	= JRequest::getVar('e_name');
		$eName	= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		?>
		<script type="text/javascript">
			function insertPagebreak()
			{
				// Get the pagebreak title
				var title = document.getElementById("title").value;
				if (title != '') {
					title = "title=\""+title+"\" ";
				}

				// Get the pagebreak toc alias -- not inserting for now
				// don't know which attribute to use...
				var alt = document.getElementById("alt").value;
				if (alt != '') {
					alt = "alt=\""+alt+"\" ";
				}

				var tag = "<hr class=\"system-pagebreak\" "+title+" "+alt+"/>";

				window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
				window.parent.document.getElementById('sbox-window').close();
				return false;
			}
		</script>

		<form>
		<table width="100%" align="center">
			<tr width="40%">
				<td class="key" align="right">
					<label for="title">
						<?php echo JText::_( 'PGB PAGE TITLE' ); ?>
					</label>
				</td>
				<td>
					<input type="text" id="title" name="title" />
				</td>
			</tr>
			<tr width="60%">
				<td class="key" align="right">
					<label for="alias">
						<?php echo JText::_( 'PGB TOC ALIAS PROMPT' ); ?>
					</label>
				</td>
				<td>
					<input type="text" id="alt" name="alt" />
				</td>
			</tr>
		</table>
		</form>
		<button onclick="insertPagebreak();"><?php echo JText::_( 'PGB INS PAGEBRK' ); ?></button>
		<?php
	}
}