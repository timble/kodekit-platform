<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Preview Toolbar Button class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesToolbarButtonPreview extends KToolbarButtonGet
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'width'   => '640',
            'height'  => '480',
        ))->append(array(
            'attribs' => array(
                'href' 	 => $this->getLink(),
                'target' => 'preview'
            )
        ));

        parent::_initialize($config);
    }
    
    /**
     * Opens up a new window that exposes the module positions in a template
     *
     * @return  string
     */
    public function getLink()
    {
        $template  = KRequest::get('get.name', 'cmd');
        $base      = KRequest::get('get.application', 'cmd', 'site') == 'admin' ? JURI::base() : JURI::root();
        $url       = $base.'index.php?tp=1&template='.KRequest::get('get.name', 'cmd');
        
        return $url;
    }
}