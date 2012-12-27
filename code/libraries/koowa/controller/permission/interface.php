<?php
/**
 * @version		$Id: executable.php 5028 2012-10-23 15:16:26Z johanjanssens $
 * @package		Koowa_Controller
 * @subpackage	Permission
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Controller Permission Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage	Permission
 */
interface KControllerPermissionInterface
{
    /**
     * Authorization handler for controller render actions
     *
     * @return  boolean  Can return both true or false.
     */
    public function canRender();

	/**
     * Authorization handler for controller browse actions
     *
     * @return  boolean Can return both true or false.
     */
    public function canBrowse();

	/**
     * Authorization handler for controller read actions
     *
     * @return  boolean Can return both true or false.
     */
    public function canRead();

	/**
     * Authorization handler for controller edit actions
     *
     * @return  boolean Can return both true or false.
     */
    public function canEdit();

 	/**
     * Authorization handler for controller add actions
     *
     * @return  boolean Can return both true or false.
     */
    public function canAdd();

 	/**
     * Authorization handler for controller delete actions
     *
     * @return  boolean Can return both true or false.
     */
    public function canDelete();
}