<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Identical values spam filter class.
 *
 * Performs a check over some selected fields and see if they have identical values.
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */

class ComUsersFilterSpamIdenticalvalues extends ComUsersFilterSpamAbstract 
{
	/**
	 * @var array The fields to be compared.
	 */
	protected $_fields;

	public function __construct(KConfig $config = null) 
	{
		if (!$config) {
			$config = new KConfig();
		}
		
		parent::__construct($config);

		$this->_fields = $config->fields->toArray();
	}

	protected function _initialize(KConfig $config) 
	{
		$config->append(array('fields' => array('name', 'username')));
		parent::_initialize($config);
	}


	protected function _validate($data) 
	{
		$data = new KConfig($data);

		$post = $data->post;

		$fields = $this->_fields;

		// Nothing to compare.
		if(count($fields) < 2) {
			return true;
		}

		$field = current($fields);
		$ref_val = $post->$field;

		while($field = next($fields)) 
		{
		    // Different values, not spammed.
		    if($post->$field != $ref_val) {
				return true;
			}
		}
		
		// Same values, spammed.
		return false;
	}
}