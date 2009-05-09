<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  Json
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View JSON Class
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 * @subpackage  Json
 */
class KViewJson extends KViewAbstract
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		//Set the correct mime type
		$this->_document->setMimeEncoding('application/json');
	}

    public function display($tpl = null)
    {
    	echo json_encode($this->getProperties());
    }
}