<?php

class ComLanguagesTemplateHelperString extends KTemplateHelperAbstract
{
    public static function status($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'status'   => '',
            'original' => 0,
            'deleted'  => 0,
        ));
        
        $arr = array(
            Nooku::STATUS_UNKNOWN    => 'Unknown',
            Nooku::STATUS_COMPLETED  => 'Completed',
            Nooku::STATUS_MISSING    => 'Missing',
            Nooku::STATUS_OUTDATED   => 'Outdated',
            Nooku::STATUS_PENDING    => 'Pending'
      	);

        if($original) 
        {
        	$text  = 'Original';
            $class = 'original';
        } 
        else 
        {
        	$text  = $arr[$status];
            $class = strtolower($arr[$status]);
        }

        $class = $deleted ? 'deleted' : $class;

        return '<span class="nooku_status '.$class.'">'
                .JText::_($text)
                .'</span>';
    }
}