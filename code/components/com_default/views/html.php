<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Default Html View Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 */
class DefaultViewHtml extends KViewHtml
{
	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
        parent::__construct($options);
        
        $template = KFactory::get('lib.koowa.application')->getTemplate();
        $override = JPATH_THEMES.DS.$template.DS.'html'.DS.'com_'.$this->_identifier->package.DS.$this->getName();
        $this->addTemplatePath($override);
	}
}