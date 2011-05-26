<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip') ?> 
<?= @helper('behavior.validator') ?>

<?
	// Initialize some variables
	$db 	=& JFactory::getDBO();

	$id 	= JRequest::getVar( 'id', 0, 'method', 'int' );
	$model	= $this->getView()->getModel();

	if ($state->application == 'admin') {
		$path				= 'mod1_xml';
	} else {
		$path				= 'mod0_xml';
	}

	$lang =& JFactory::getLanguage();
	if($state->application == 'site') {
		$lang->load( trim($module->module), JPATH_SITE );
	} else {
		$lang->load( trim($module->module) );
	}

	// xml file for module
	if ($module->module == 'custom') {
		$xmlfile = JApplicationHelper::getPath( $path, 'mod_custom' );
	} else {
		$xmlfile = JApplicationHelper::getPath( $path, $module->module );
	}

	$data = JApplicationHelper::parseXMLInstallFile($xmlfile);
	if ($data)
	{
		foreach($data as $key => $value) {
			$module->$key = $value;
		}
	}

	// get params definitions
	$params = new JParameter( $module->params, $xmlfile, 'module' );

?>

<script type="text/javascript">
window.addEvent('domready', function() { 
	<? /* @TODO @route needs to be updated to handle js contexts, using JRoute for now */ ?>
	var list		= $('ordering'),
		position	= list.get('value'),
		cache       = {},
		setList     = function(data){
		    var options = [];
		    Hash.each(data, function(module){
		    	options.include(new Element('option', {
		    		selected: <?= json_encode($module->ordering) ?> == module.ordering,
		    		value: module.ordering,
		    		text: module.ordering+'::'+module.title
		    	}));
		    });
		    
		    list.empty().adopt(options);
		},
		request 	= new Request.JSON({
			url: <?= json_encode(JRoute::_('index.php?option=com_modules&view=modules&format=json&application='.$state->application, false)) ?>,
			/* @TODO change onComplete to onSuccess, and add onFailure */
			onComplete: function(data){
			    cache[position] = data;
			    setList(data);
			}
		});

	$$('#combobox-position-select', '#position').addEvent('change', function(){
	    position = this.get('value');
	    cache[position] ? setList(cache[position]) : request.get({position: position});
	}).fireEvent('change');
	
	//Sets a placeholder for the default position, for usability
	$('position').set('placeholder', position);
});
</script>

<form action="<?= @route('id='.$module->id.'&application='.$state->application) ?>" method="post" class="-koowa-form">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?= @text( 'Details' ) ?></legend>

		<table class="admintable">
			<tr>
				<td valign="top" class="key">
					<?= @text('Module Type') ?>:
				</td>
				<td>
					<strong>
						<?= @text($module->module) ?>
					</strong>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="title">
						<?= @text('Title') ?>:
					</label>
				</td>
				<td>
					<input class="text_area required" type="text" name="title" id="title" size="35" value="<?= @escape($module->title) ?>" />
				</td>
			</tr>
			<? if($state->application == 'site') : ?>
			<tr>
				<td width="100" class="key">
					<?= @text('Show title') ?>:
				</td>
				<td>
					<?= @helper('select.booleanlist', array(
						'name'		=> 'showtitle',
						'selected'	=> $module->showtitle
					)) ?>
				</td>
			</tr>
			<? endif ?>
			<tr>
				<td valign="top" class="key">
					<?= @text('Published') ?>:
				</td>
				<td>
					<?= @helper('select.booleanlist', array(
						'name'		=> 'enabled',
						'selected'	=> $module->enabled	
					)) ?>
				</td>
			</tr>
			<tr>
				<td valign="top" class="key">
					<label for="position" class="hasTip" title="<?= @text('MODULE_POSITION_TIP_TEXT', true) ?>">
						<?= @text('Position') ?>:
					</label>
				</td>
				<td>
				    <?= @helper('combobox.positions', array('application' => $state->application)) ?>
				</td>
			</tr>
			<tr>
				<td valign="top"  class="key">
					<label for="ordering">
						<?= @text( 'Order' ) ?>:
					</label>
				</td>
				<td>
					<?= @helper('select.optionlist', array('name' => 'ordering', 'attribs' => array('id' =>'ordering'))) ?>
				</td>
			</tr>
			<? if($state->application == 'site') : ?>
			<tr>
				<td valign="top" class="key">
					<label for="access">
						<?= @text('Access Level') ?>:
					</label>
				</td>
				<td>
					<?= JHTML::_('list.accesslevel', $module) ?>
				</td>
			</tr>
			<? endif ?>
			<? if($module->id) : ?>
			<tr>
				<td valign="top" class="key">
					<?= @text('ID') ?>:
				</td>
				<td>
					<?= $module->id ?>
				</td>
			</tr>
			<? endif ?>
			<tr>
				<td valign="top" class="key">
					<?= @text('Description') ?>:
				</td>
				<td>
					<?= @text($module->description) ?>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<? if($state->application == 'site') : ?>
	
	<script type="text/javascript">
	window.addEvent('domready', function(){
		var selections = $('selections'),
			setSelections = function(disabled, selected){
				this.disabled = disabled;
				$$(this.options).each(function(option){
					option.disabled = disabled;
					if(selected !== null) option.selected = selected;
				});
			};
		$('menus-all').addEvent('change', function(){
			setSelections.call(selections, true, true);
		});
		$('menus-none').addEvent('change', function(){
			setSelections.call(selections, true, false);
		});
		$('menus-select').addEvent('change', function(){
			setSelections.call(selections, false, null);
		});
	
		<? if($state->application == 'site') : ?>
			<? if($module->pages == 'all') : ?>
				$('menus-all').fireEvent('change');
			<? endif ?>
			<? if($module->pages == 'none') : ?>
				$('menus-none').fireEvent('change');
			<? endif ?>
		<? endif ?>
	});
	</script>
	
	<fieldset class="adminform">
		<legend><?= @text('Menu Assignment') ?></legend>
		<table class="admintable" cellspacing="1">
			<tr>
				<td valign="top" class="key">
					<?= @text( 'Menus' ) ?>:
				</td>
				<td>
				<? if(!$module->client_id) : ?>
					<label for="menus-all">
						<input id="menus-all" type="radio" name="pages" value="all" <? if($module->pages == 'all') echo 'checked="checked"' ?> />
						<?= @text('All') ?>
					</label>
					<label for="menus-none">
						<input id="menus-none" type="radio" name="pages" value="none" <? if($module->pages == 'none') echo 'checked="checked"' ?> />
						<?= @text('None') ?>
					</label>
					<label for="menus-select">
						<input id="menus-select" type="radio" name="pages" value="select" <? if(is_array($module->pages)) echo 'checked="checked"' ?> />
						<?= @text('Select From List') ?>
					</label>
				<? endif ?>
				</td>
			</tr>
			<tr>
				<td valign="top" class="key">
					<?= @text('Menu Selection') ?>:
				</td>
				<td>
					<?= JHTML::_('select.genericlist', JHTML::_('menu.linkoptions'), 'pages[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $module->pages, 'selections' ) ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<? endif ?>
</div>

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?= @text('Parameters') ?></legend>
		
		<?= @helper('accordion.startPane', array('id' => 'menu-pane')) ?>
			<?= @template('form_accordion', array('params' => $params, 'id' => 'param-page', 'title' => 'Module Parameters')) ?>

			<? if($params->getNumParams('advanced')) : ?>
			<?= @template('form_accordion', array('params' => $params, 'group' => 'advanced')) ?>
			<? endif ?>
		
			<? if($params->getNumParams('other')) : ?>
			<?= @template('form_accordion', array('params' => $params, 'group' => 'other')) ?>
			<? endif ?>
		<?= @helper('accordion.endPane') ?>
	</fieldset>
</div>
<div class="clr"></div>

<? if(!$module->module || $module->module == 'custom' || $module->module == 'mod_custom') : ?>
<fieldset class="adminform">
		<legend><?= @text('Custom Output') ?></legend>
		
		<?= @editor(array(
			'name'		=> 'content',
			//@TODO is escaping the module content really necessary?
			'content'	=> @escape($module->content),
			'height'	=> 400,
			'cols'		=> 60,
			'buttons'	=> array('pagebreak', 'readmore')
		)) ?>

</fieldset>
<? endif ?>

<input type="hidden" name="module" value="<?= $module->module ?>" />
</form>