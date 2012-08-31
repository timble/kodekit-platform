<?php
class ComLanguagesTemplateHelperGrid extends KTemplateHelperGrid
{
    public function status($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'status'   => '',
            'original' => 0,
            'deleted'  => 0
        ));
    
        $statuses = array(
            ComLanguagesDatabaseRowTranslation::STATUS_UNKNOWN   => 'Unknown',
            ComLanguagesDatabaseRowTranslation::STATUS_COMPLETED => 'Completed',
            ComLanguagesDatabaseRowTranslation::STATUS_MISSING   => 'Missing',
            ComLanguagesDatabaseRowTranslation::STATUS_OUTDATED  => 'Outdated',
            ComLanguagesDatabaseRowTranslation::STATUS_PENDING   => 'Pending'
        );
        
        $text  = $config->original ? 'Original' : $statuses[$config->status];
        $class = $config->original ? 'original' : strtolower($statuses[$config->status]);
        $class = $config->deleted ? 'deleted' : $class;
    
        return '<span class="label label-'.$class.'">'.JText::_($text).'</span>';
    }
}