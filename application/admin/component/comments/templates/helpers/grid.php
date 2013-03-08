<?php

use Nooku\Framework;

class ComCommentsTemplateHelperGrid extends Framework\TemplateHelperGrid
{   
    public function gravatar($config = array())
    {
        $config = new Framework\Config($config);
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
