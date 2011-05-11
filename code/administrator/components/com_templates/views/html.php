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
    	    $config->template_url = KRequest::root() . ($state->client ? '/administrator' : '') . '/templates';
    	}
        
        // Set base url used by things like template thumbnails
        $this->assign('templateurl', $config->template_url);
        
        $client	= KRequest::get('get.client', 'int', $this->getModel()->getState()->client);
        
        JSubMenuHelper::addEntry(JText::_('Site'), 'index.php?option=com_templates&view=templates&client=0',          $client == 0);
        JSubMenuHelper::addEntry(JText::_('Administrator'), 'index.php?option=com_templates&view=templates&client=1', $client == 1);
    }

    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'template_url' => false
       	));
    	
    	parent::_initialize($config);
    }
}