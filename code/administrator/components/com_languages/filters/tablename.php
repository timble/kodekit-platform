<?php

class ComLanguagesFilterTablename extends KFilterAbstract
{
	public function validate($var)
	{
		if(strlen($var) > 64) {
			return false;
		}
		
		// TODO do a proper filter for tablenames in nooku
	   	return KFactory::get('lib.koowa.filter.cmd')->validate($var);
	}
	
	public function sanitize($var)
	{
		// TODO do a proper filter for tablenames in nooku
    	return substr(KFactory::get('lib.koowa.filter.cmd')->sanitize($var), 0, 64);
	}
}