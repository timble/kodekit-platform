<? if(JFactory::getApplication()->getCfg('multilang')) : ?>
    <?= @helper('behavior.mootools') ?>
    <script>
    window.addEvent('domready', function(){
        document.getElement('select[name=language]').addEvent('change', function(){
            window.location = this.value;
        });
    });
    </script>
    
    <module position="status" content=append>
        <?= @helper('com://admin/languages.template.helper.listbox.languages') ?>
    </module>
<? endif ?>