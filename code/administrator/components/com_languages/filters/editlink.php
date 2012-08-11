<?php

class ComLanguagesFilterEditlink extends KFilterAbstract
{
	public function validate($var)
	{
		$var = trim($var);
		return substr($var, 0, 10) == 'index.php?'
				&& strpos($var, 'option=com_') !== false
				&& false !== filter_var('http://dummy.com/'.$var, FILTER_VALIDATE_URL);
	}
	
	public function sanitize($var)
	{
		// the trick with dummy.com is a bit ugly, but it's easier to have php do all the hard work :-)
		$var =  substr(filter_var('http://dummy.com/'.$var, FILTER_SANITIZE_URL), 17);		
		if(substr($var, 0, 10) != 'index.php?' || strpos($var, 'option=com_') === false ) {
			// ntohing we can do if we don't have these essential elements
			return null;
		}
		
		return $var;
	}
}

