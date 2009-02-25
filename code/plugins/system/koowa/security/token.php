<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Security
 * @subpackage	Token
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Utility class to work with tokens in forms, to prevent CSRF attacks
 *
 * @example:
 * In a form:
 * <code>
 * <?php echo KSecurityToken::render();?>
 * </code>
 * Where the form is submitted:
 * <code>
 * <?php KSecurityToken::check() or die('Invalid Token'); ?>
 * </code>
 * 
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Security
 * @subpackage	Token
 */
class KSecurityToken
{
	/**
	 * Token
	 *
	 * @var	string
	 */
	protected static $_token;
	
    /**
     * Generate new token and store it in the session
     * 
     * @param	bool	Reuse from session (defaults to false, useful for ajax forms)
     * @return	string	Token
     */
    static public function get($reuse = false)
    {
        if(!isset(self::$_token))
        {
        	$session 		= KFactory::get('lib.joomla.session');
        	if($reuse && $token = $session->get('koowa.security.token')) {
        		// Re-use the previous token from the session
        		self::$_token = $token;
        	} else {
        		// Generate a new token
        		self::$_token = md5(uniqid(rand(), TRUE));
        	}

            $session->set('koowa.security.token', self::$_token);
            $session->set('koowa.security.tokentime', time());
        }

        return self::$_token;
    }

    /**
     * Render the hidden input field with the token
     *
     * @param	bool	Reuse from session (defaults to false, useful for ajax forms)
     * @return	string	Html hidden input field
     */
    static public function render($reuse = false)
    {
    	return '<input type="hidden" name="_token" value="'.self::get($reuse).'" />';
    }

    /**
     * Check if a valid token was submitted
     *
     * @param 	boolean	Maximum age, defaults to 600 seconds
     * @return	boolean	True on success
     */
    static public function check($max_age = 600)
    {
    	$session	= KFactory::get('lib.joomla.session');
        $token		= $session->get('koowa.security.token', null);
		$age 		= time() - $session->get('koowa.security.tokentime');
		
        $req		= KInput::get('_token', 'post', 'md5'); 
		
        return ($req===$token && $age <= $max_age);
    }
}