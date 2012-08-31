<? if(@service('application')->getCfg('multilanguage')) : ?>
    <? $component = @service('com://admin/extensions.model.components')->name('com_'.$component)->getList()->top() ?>
    <? $tables = @service('com://admin/languages.model.tables')->enabled(true)->getList() ?>
    
    <? if(count($tables->find(array('component' => $component->id)))) : ?>
        <?= @helper('behavior.mootools') ?>
        <script>
        window.addEvent('domready', function(){
            document.getElement('select[name=language]').addEvent('change', function(){
                window.location = this.value;
            });
        });
        </script>
        
        <ktml:module position="toolbar" content="append">
            <?= @helper('com://admin/languages.template.helper.listbox.languages') ?>
        </ktml:module>
    <? endif ?>
<? endif ?>