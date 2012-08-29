<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

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
			url: <?= json_encode((string) @route('view=modules&format=json&application='.$state->application, false, false)) ?>,
			/* @TODO change onComplete to onSuccess, and add onFailure */
			onComplete: function(data){
			    cache[position] = data;
			    setList(data);
			}
		});
});
</script>

<form action="<?= @route('id='.$module->id.'&application='.$state->application) ?>" method="post" class="-koowa-form">
	<div class="form-body">
		<div class="title">
			<input class="required" type="text" name="title" value="<?= @escape($module->title) ?>" />
		</div>
		
		<div class="form-content">
		    <fieldset class="form-horizontal">
		    	<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label"><?= @text('Type') ?></label>
				    <div class="controls">
				        <?= @text(ucfirst($module->identifier->package)).' &raquo; '. @text(ucfirst($module->identifier->path[1])); ?>
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
						
			<? if($module->name == 'mod_custom') : ?>
			<fieldset>
				<legend><?= @text('Custom Output') ?></legend>
				
				<?= @service('com://admin/editors.controller.editor')
					->name('content')
					->data($module->content)
					->display() ?>
			</fieldset>
			<? endif ?>
		</div>
	</div>

	<div class="sidebar">
		<div class="scrollable">	
			<fieldset class="form-horizontal">
				<legend><?= @text('Publish') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="enabled"><?= @text('Published') ?></label>
				    <div class="controls">
				        <input type="checkbox" name="enabled" value="1" <?= $module->enabled ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="showtitle"><?= @text('Show title') ?></label>
				    <div class="controls">
				        <input type="checkbox" name="showtitle" value="1" <?= $module->showtitle ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Position') ?></label>
				    <div class="controls">
                        <?= @helper('listbox.positions', array('application' => $state->application, 'deselect' => false)) ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Visibility') ?></label>
				    <div class="controls">
				        <?= @helper('listbox.access', array('selected' => $module->access, 'deselect' => false)) ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>

	<input type="hidden" name="name" value="<?= $module->name ?>" />
	<input type="hidden" name="application" value="<?= $module->application ?>" />
</form>