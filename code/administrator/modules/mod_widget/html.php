<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Modules
 * @subpackage  Widget
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Widget Module Html View Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Modules
 * @subpackage  Widget
 */
 
class ModWidgetHtml extends ModDefaultHtml
{
    public function display()
    {
    	$this->setLayout($this->module->params->get('layout', 'overlay'));
    	
    	//Create the url object and force the tmpl to empty
    	$url = $this->getService('koowa:http.url', array('url' => $this->module->params->get('url')));
    	
    	$this->assign('url', $url);
    	
    	return parent::display();
    }
} 