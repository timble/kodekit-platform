<?php
/**
 * @version     $Id: html.php 1437 2011-05-23 17:46:22Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template HTML View Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions    
 */
class ComExtensionsViewTemplateHtml extends ComDefaultViewHtml
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
    	if(!$config->template_url) 
    	{
    	    $state                = $this->getModel()->getState();
    	    $config->template_url = KRequest::root() . ($state->application == 'admininistrator' ? '/administrator' : '') . '/templates';
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