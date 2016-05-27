<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-ckeditor
 */

namespace Kodekit\Component\Ckeditor;

use Kodekit\Library;

/**
 * Editor Html View Class
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Component\Ckeditor
 */
class ViewEditorHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $locale = $this->getObject('translator')->getLanguage();

        $config->append(array(
            'options' => array(
                'baseHref'		    => '/files/'.$this->getObject('application')->getSite().'/',
                'language'	        => substr($locale, 0, strpos( $locale, '-' )),
                'contentsLanguage'  => substr($locale, 0, strpos( $locale, '-' )),
                'height' 	        => '',
                'width'			    => '',
                'removeButtons'	    => '',
                'autoheight'        => true,
                'toolbar'           => $this->toolbar ? $this->toolbar : 'standard',
            )
        ));

        parent::_initialize($config);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        //Set editor id
        if(!$context->data->id) {
            $context->data->id = $context->data->name;
        }

        //Set editor options
        $context->data->append(array('options' => $this->getConfig()->options));

        //Set editor class
        $context->data->class = isset($this->attribs['class']) ? $this->attribs['class'] : '';

        parent::_fetchData($context);
    }
}