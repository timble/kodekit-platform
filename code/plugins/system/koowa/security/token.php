<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Security
 * @subpackage	Token
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Utility class to work with tokens in forms, to prevent CSRF attacks
 *
 * In a form:
 * <code>
 * <?php echo KSecurityToken::render();?>
 * or <?= @token ?> // Koowa adds these automatically
 * </code>
 * Where the form is submitted:
 * <code>
 * <?php KSecurityToken::check() or die('Invalid Token'); ?>
 * </code>
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Security
 * @subpackage	Token
 */
class KSecurityToken
{
	/**
     * Generate new token and store it in the session
     * 
     * @param	bool	Force to generate a new token
     * @return	string	Token
     */
    static public function get($forceNew = false)
    {
    	return  JUtility::getToken($forceNew);
    }

    /**
     * Render the hidden input field with the token
     *
     * @return	string	Html hidden input field
     */
    static public function render()
    {
    	return '<input type="hidden" name="_token" value="'.self::get().'" />';
    }

    /**
     * Check if a valid token was submitted
     *
     * @return	boolean	True on success
     */
    static public function check()
    {
		// Using getVar instead of getString, because if the request is not a string, 
		// we consider it a hacking attempt
        $req		= KRequest::get('post._token', 'md5'); 
        $token		= self::get();
        
        return ($req === $token);
    }
}