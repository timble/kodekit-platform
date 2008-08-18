<?php
/**
 * @version     $Id$
 * @package     Koowa_View
 * @subpackage  Json
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View JSON Class
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_View
 * @subpackage  Json
 */
class KViewJson extends KViewAbstract
{
	public function __construct($options = array())
	{
		parent::__construct($options);

		//Set the correct mime type
		$this->document->setMimeEncoding('application/json');
	}

    public function assign($val)
    {
        if(func_num_args() == 1) {
        	parent::assign('json', $val);
        }

        $args = func_get_args();
        return call_user_func_array(array($this, 'parent::assign'), $args);
    }

    public function assignRef($val)
    {
        if(func_num_args() == 1) {
            parent::assignRef('json', $val);
        }

        $args = func_get_args();
        return call_user_func_array(array($this, 'parent::assignRef'), $args);
    }

    public function display($tpl = null)
    {
        if(isset($this->json)) {
            echo json_encode($this->json);
        }
    }
}