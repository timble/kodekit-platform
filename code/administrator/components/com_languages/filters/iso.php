<?php

class ComLanguagesFilterIso extends KFilterCmd
{
    protected function _validate($value)
    {
        $value = trim($value);
        $pattern = '#^[a-z]{2,3}\-[a-z]{2,3}$#i';
        
        return (is_string($value) && (preg_match($pattern, $value)) == 1);
    }

    protected function _sanitize($value)
    {
        $value = trim($value);
        $pattern  = '#[^a-z\-]*#i';
        
        return preg_replace($pattern, '', $value);
    }
}