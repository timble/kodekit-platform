<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * HTML View class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesViewHtml extends ComDefaultViewHtml
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
    	if(!$config->template_url) 
    	{
    	    $state                = $this->getModel()->getState();
    	    $config->template_url = KRequest::root() . ($state->application == 'admin' ? '/administrator' : '') . '/templates';
    	}
        
        // Set base url used by things like template thumbnails
        $this->assign('templateurl', $config->template_url);
    }

    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'template_url' => false
       	));
    	
    	parent::_initialize($config);
    }
}