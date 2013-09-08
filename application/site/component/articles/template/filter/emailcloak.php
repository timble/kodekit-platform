<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Email Cloak Template Filter
 *
 * Obfuscates email address using JavaScript for preventing bot email address harvesting.
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesTemplateFilterEmailcloak extends Library\TemplateFilterAbstract implements Library\TemplateFilterRenderer
{
    /**
     * Determines if email address should be linked
     *
     * @var boolean
     */
    protected $_linkable;

    /**
     * n associative array containing patterns.
     *
     * @var array
     */
    protected $_patterns = array(
        'email' => '[\w\.\-]+\@(?:[a-z0-9\.\-]+\.)+(?:[a-z0-9\-]{2,4})',
        'query' => '(?:[?&][^?&"]+)*'
    );

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_linkable = $config->linkable;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('linkable' => true));
        parent::_initialize($config);
    }

    /**
     * Pattern getter.
     *
     * @param string $name The title of the pattern
     * @return null|string The pattern, null if there's no patter with provided title.
     */
    protected function _getPattern($name)
    {
        $result = null;
        if (isset($this->_patterns[$name])) {
            $result = $this->_patterns[$name];
        }
        return $result;
    }

    public function render(&$text)
    {
        // Search for <a href="mailto:|http(s)://mce_host/dir/email@email.tld">
        $pattern = '~<a[^>]*href\s*=\s*"(?:mailto:|https?://.+?)';
        $pattern .= '(';
        $pattern .= $this->_getPattern('email');
        // Include the query (if any)
        $pattern .= $this->_getPattern('query');
        $pattern .= ')';
        $pattern .= '.*?>';
        // The text of the URL: if could be text, email, an image, etc.
        $pattern .= '(.*?)';
        $pattern .= '</a>~i';

        while (preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE))
        {
            $text = substr_replace($text, $this->_cloak($matches[1][0], $matches[2][0]), $matches[0][1],
                strlen($matches[0][0]));
        }

        // Search for email@amail.tld
        $pattern = '~(' . $this->_getPattern('email') . ')[\W]~i';
        while (preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE))
        {
            $text = substr_replace($text, $this->_cloak($matches[1][0]), $matches[1][1],
                strlen($matches[1][0]));
        }
    }

    /**
     * Simple Javascript email cloaker
     *
     * By default replaces an email with a mailto link with email cloacked
     *
     * @param string $email
     * @param string $text
     * @return string The cloacked email
     */
    protected function _cloak($email, $text = '')
    {
        // Random var suffix.
        $rand = rand(1, 100000);

        // Obfuscate inputs.
        $email = $this->_encode($email);

        // Split email address
        $email_parts = explode('@', $email);

        $output = "\n <script data-inline type='text/javascript'>";
        $output .= "\n <!--";
        $output .= "\n var path = 'hr' + 'ef' + '=';";
        $output .= "\n var addy{$rand} = '" . $email_parts[0] . "' + '&#64;';";
        $output .= "\n addy{$rand} = addy{$rand} + '";
        $output .= implode('\' + \'&#46;\' + \'', explode('.', $email_parts[1])) . '\';';

        if ($this->_linkable)
        {
            // Render a linkable email address.
            $output .= "\n document.write('<a ' + path + '\'mailto:' + addy{$rand} + '\'>');";
            if ($text)
            {
                if ($this->_isEmail($text))
                {
                    $text       = $this->_encode($text);
                    $text_parts = explode('@', $text);
                    $output .= "\n var text{$rand} = '{$text_parts[0]}' + '&#64;';";
                    $output .= "\n text{$rand} = text{$rand} + '";
                    $output .= implode('\' + \'&#46;\' + \'', explode('.', $text_parts[1])) . '\';';
                }
                else $output .= "\n var text{$rand} = '{$text}';";

                $output .= "\n document.write(text{$rand});";
            }
            else  $output .= "\n document.write(addy{$rand});"; // Use email address as link text.

            $output .= "\n document.write('</a>');";
        }
        else $output .= "\n document.write( addy{$rand});"; // Do not link, just render the email address.


        $output .= "\n //-->";
        $output .= "\n </script>";

        // XHTML compliance `No JavaScript` text handling
        $output .= "<script data-inline type='text/javascript'>";
        $output .= "\n <!--";
        $output .= "\n document.write( '<span style=\'display: none;\'>' );";
        $output .= "\n //-->";
        $output .= "\n </script>";
        $output .= $this->translate('CLOAKING');
        $output .= "\n <script data-inline type='text/javascript'>";
        $output .= "\n <!--";
        $output .= "\n document.write( '</' );";
        $output .= "\n document.write( 'span>' );";
        $output .= "\n //-->";
        $output .= "\n </script>";

        return $output;
    }

    /**
     * Determines if text is an email address.
     *
     * @param $text The text to test.
     * @return bool True if email address, false otherwise.
     */
    protected function _isEmail($text)
    {
        $result  = false;
        $pattern = '~' . $this->_getPattern('email') . '~i';
        if (preg_match($pattern, $text, $matches)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Text encoder.
     *
     * @param string $text Text to encode.
     * @return string Encoded text.
     */
    protected function _encode($text)
    {
        $search  = array('a', 'e', 'i', 'o', 'u');
        $replace = array('&#97;', '&#101;', '&#105;', '&#111;', '&#117;');

        // Replace with HTML entities.
        return str_replace($search, $replace, $text);
    }
}