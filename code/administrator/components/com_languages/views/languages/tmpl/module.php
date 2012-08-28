<? if(@service('application')->getCfg('multilanguage')) : ?>
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