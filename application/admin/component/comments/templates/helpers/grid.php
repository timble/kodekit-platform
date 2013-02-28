<?php
class ComCommentsTemplateHelperGrid extends KTemplateHelperGrid
{   
    public function gravatar($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'email'  => '',
            'size'  => '32',
            'attribs' => array()
        ));
        
        $source = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $config->email ) ) ) . "?s=".$config->size;

        $html = '<img class="avatar" src="'.$source.'" />';

        return $html;
    }
}
