<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip') ?> 
<?= @helper('behavior.validator') ?>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<script type="text/javascript">
window.addEvent('domready', function() { 
	<? /* @TODO @route needs to be updated to handle js contexts, using JRoute for now */ ?>
	var list		= $('ordering'),
		position	= list.get('value'),
		cache       = {},
		setList     = function(data){
		    var options = [];
		    Hash.each(data.items, function(module){
		    	options.include(new Element('option', {
		    		selected: <?= json_encode($module->ordering) ?> == module.ordering,
		    		value: module.ordering,
		    		text: module.ordering+'::'+module.title
		    	}));
		    });
		    
		    list.empty().adopt(options);
		},
		request 	= new Request.JSON({
			url: <?= json_encode(JRoute::_('index.php?option=com_extensions&view=modules&format=json&application='.$state->application, false)) ?>,
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

<form action="<?= @route('id='.$module->id.'&application='.$state->application) ?>" method="post" class="-koowa-form -koowa-box">
	<div class="-koowa-box-vertical -koowa-box-flex1">
		<div class="title">
			<input class="required" type="text" name="title" value="<?= @escape($module->title) ?>" />
		</div>
		
		<div class="-koowa-box-flex1 -koowa-box-scroll" style="padding: 20px;">
		    <fieldset class="form-horizontal">
		    	<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label"><?= @text('Type') ?></label>
				    <div class="controls">
				        <?= @text($module->type) ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label"><?= @text('Description') ?></label>
				    <div class="controls">
				        <?= @text($module->description) ?>
				    </div>
				</div>
			</fieldset>
			
			<?= @helper('tabs.startPane') ?>
				<?= @helper('tabs.startPanel', array('id' => 'default', 'title' => 'Default Parameters')) ?>
					<?= @template('form_accordion', array('params' => $module->params)) ?>
				<?= @helper('tabs.endPanel') ?>				
				
				<? if($module->params->getNumParams('advanced')) : ?>
				<?= @helper('tabs.startPanel', array('id' => 'advanced', 'title' => 'Advanced Parameters')) ?>
				<?= @template('form_accordion', array('params' => $module->params, 'group' => 'advanced')) ?>
				<?= @helper('tabs.endPanel') ?>
				<? endif ?>
				
				<? if($module->params->getNumParams('other')) : ?>
				<?= @helper('tabs.startPanel', array('id' => 'other', 'title' => 'Other Parameters')) ?>
				<?= @template('form_accordion', array('params' => $module->params, 'group' => 'other')) ?>
				<?= @helper('tabs.endPanel') ?>
				<? endif ?>
			<?= @helper('tabs.endPane') ?>
						
			<? if(!$module->type || $module->type == 'custom' || $module->type == 'mod_custom') : ?>
			<fieldset>
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
		</div>
	</div>

	<div id="sidebar" style="width: 300px;">		
		<fieldset class="form-horizontal">
			<legend><?= @text('Publish') ?></legend>
			<? if($state->application == 'site') : ?>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text('Show title') ?></label>
			    <div class="controls controls-radio">
			        <?= @helper('select.booleanlist', array(
			        	'name'		=> 'showtitle',
			        	'selected'	=> $module->showtitle
			        )) ?>
			    </div>
			</div>
			<? endif ?>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text('Published') ?></label>
			    <div class="controls controls-radio">
			        <?= @helper('select.booleanlist', array(
			        	'name'		=> 'enabled',
			        	'selected'	=> $module->enabled	
			        )) ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text('Position') ?></label>
			    <div class="controls">
			        <?= @helper('combobox.positions', array('application' => $state->application)) ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text( 'Order' ) ?></label>
			    <div class="controls">
			        <?= @helper('select.optionlist', array('name' => 'ordering', 'attribs' => array('id' =>'ordering'))) ?>
			    </div>
			</div>
			<? if($state->application == 'site') : ?>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text('Access Level') ?></label>
			    <div class="controls">
			        <?= JHTML::_('list.accesslevel', $module) ?>
			    </div>
			</div>
			<? endif ?>
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
		
		<fieldset class="form-horizontal">
			<legend><?= @text('Menu Assignment') ?></legend>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text( 'Menus' ) ?></label>
			    <div class="controls">
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
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text('Selection') ?></label>
			    <div class="controls">
			        <?= JHTML::_('select.genericlist', JHTML::_('menu.linkoptions'), 'pages[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $module->pages, 'selections' ) ?>
			    </div>
			</div>
		</fieldset>
		
		<? endif ?>
	</div>

	<input type="hidden" name="type" value="<?= $module->type ?>" />
	<input type="hidden" name="client_id" value="<?= $module->client_id ?>" />
</form>