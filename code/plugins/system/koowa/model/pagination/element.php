<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @subpackage	Pagination
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Pagination Element, contains all information about a single page link
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Model
 * @subpackage	Pagination
 */
class KModelPaginationElement extends KObject
{
	/**
	 * Page number
	 * @var int
	 */
	public $page;

	/**
	 * Offset
	 * @var int
	 */
	public $offset;

	/**
	 * Text representation
	 * @var string
	 */
	public $text;

	/**
	 * Is the item active?
	 * @var boolean
	 */
	public $active;

	/**
	 * Is this the current page
	 * @var boolean
	 */
	public $current;

}