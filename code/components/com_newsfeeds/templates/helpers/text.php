<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Text Template Helper
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

class ComNewsfeedsTemplateHelperText extends KTemplateHelperDefault
{
    public function limit( $config = array() )
    {
        $config = new KConfig($config);
        $config->append(array(
            'words'     => 0,
            'indicator' => '...',
            'quotes'    => true
        ));
        
        $text = $config->text;

        if($config->words > 0) 
        {
            $words = explode(' ', $text);
            $count = count($words);

            if($count > $config->words)    
            {
                $text = '';
                for( $i=0; $i < $config->words; $i++ ) {
                    $text .= ' '. $words[$i];
                }
                
                $text .= $config->indicator;
            }

            if($config->quotes) {
                str_replace('&apos;', "'", $text);
            }
        }

        return $text;
    }
}