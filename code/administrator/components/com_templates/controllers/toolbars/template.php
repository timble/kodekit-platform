<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Templates Toolbar Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComTemplatesControllerToolbarTemplates extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $this->insert('divider')
             ->insert('preview');
        
    }
   
    protected function _commandPreview(KControllerToolbarCommand $command)
    {
        $template  = KRequest::get('get.name', 'cmd');
        $base      = KRequest::get('get.application', 'cmd', 'site') == 'admin' ? JURI::base() : JURI::root();
        
        $command->append(array(
            'width'   => '640',
            'height'  => '480',
        ))->append(array(
            'attribs' => array(
                'href' 	 =>  $base.'index.php?tp=1&template='.$template,
                'target' => 'preview'
            )
        ));
    }
}