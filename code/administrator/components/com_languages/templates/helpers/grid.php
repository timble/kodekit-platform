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
            ComLanguagesDatabaseRowItem::STATUS_UNKNOWN   => 'Unknown',
            ComLanguagesDatabaseRowItem::STATUS_COMPLETED => 'Completed',
            ComLanguagesDatabaseRowItem::STATUS_MISSING   => 'Missing',
            ComLanguagesDatabaseRowItem::STATUS_OUTDATED  => 'Outdated',
            ComLanguagesDatabaseRowItem::STATUS_PENDING   => 'Pending'
        );
        
        $text  = $config->original ? 'Original' : $statuses[$config->status];
        $class = $config->original ? 'original' : strtolower($statuses[$config->status]);
        $class = $config->deleted ? 'deleted' : $class;
    
        return '<span class="label label-'.$class.'">'.JText::_($text).'</span>';
    }
}