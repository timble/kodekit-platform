<?php
class ComLanguagesTemplateHelperGrid extends KTemplateHelperGrid
{
    public function flag($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'iso_code' => 'en-GB',
            'url'      => 'media://com_languages/images/flags/'
        ));
        
        $languages = clone $this->getTemplate()->getView()->languages;
        $language  = $languages->find(array('iso_code' => $config->iso_code))->top();
        
        if($language) {
            $image = $config->url.$language->image;
        }
        else
        {
            if(!strpos($config->iso_code, '-')) {
                $image = 'unknown.png';
            }
            else
            {
                list($language, $country) = explode('-', $config->iso_code, 2);
                $image = $config->url.$country.'.png';
            }
        }
        
        return '<div class="languages-flag" style="background-image: url('.$image.');" title="'.$config->iso_code.'"></div>';
    }
    
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
    
        if($config->original)
        {
            $text  = 'Original';
            $class = 'original';
        }
        else
        {
            $text  = $statuses[$config->status];
            $class = strtolower($statuses[$config->status]);
        }
    
        $class = $config->deleted ? 'deleted' : $class;
    
        return '<span class="languages-status '.$class.'">'.JText::_($text).'</span>';
    }
}