<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Email Cloak Template Filter Class
 *
 * Obfuscates email address using JavaScript for preventing bot email address harvesting.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesTemplateFilterEmailcloak extends KTemplateFilterAbstract implements KTemplateFilterWrite
{
    /**
     * @var boolean Determines if email address should be linked.
     */
    protected $_linkable;

    /**
     * @var array Regular expressions.
     */
    protected $_regexps = array(
        'email' => '([\w\.\-]+\@(?:[a-z0-9\.\-]+\.)+(?:[a-z0-9\-]{2,4}))',
        'query' => '([?&][\x20-\x7f][^"<>]+)',
        'text'  => '((?:[\x20-\x7f]|[\xA1-\xFF]|[\xC2-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF4][\x80-\xBF]{3})[^<>]+)',
        'image' => '(<img[^>]+>)');

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->_linkable = $config->linkable;
    }

    protected function _initialize(KConfig $config) {
        $config->append(array('linkable' => true));
        parent::_initialize($config);
    }

    /**
     * Genarates a pattern that matches a link with the provided url and text regexp keys.
     *
     * @param mixed The regexp key(s) for matching the url of the email link.
     * @param mixed The regexp key(s) for matching the text enclosed by the link.
     *
     * @return string The regular expression.
     */
    protected function _getPattern($url, $text) {
        return '~<a [\w "\'=\@\.\-]*href\s*=\s*"(?:mailto:|https?://mce_host[\x20-\x7f][^<>]+/)'
            . $this->_getRegExp($url) . '"[\w "\'=\@\.\-]*>' . $this->_getRegExp($text) . '</a>~i';
    }

    /**
     * Regular expression getter.
     *
     * @param mixed $name The name of the regular expression.
     *
     * @return string The regular expression.
     */
    protected function _getRegExp($name) {
        $regexp = '';
        if (is_array($name)) {
            // Generate a composite regular expression.
            foreach ($name as $key) {
                $regexp .= $this->_regexps[$key];
            }
        } else {
            $regexp = $this->_regexps[$name];
        }
        return $regexp;
    }

    public function write(&$text) {
        // Search for <a href="mailto:|http(s)://mce_host/dir/email@amail.tld">email@amail.tld</a>
        while (preg_match($this->_getPattern('email', 'email'), $text, $matches, PREG_OFFSET_CAPTURE)) {
            $text = substr_replace($text, $this->_cloak($matches[1][0]), $matches[0][1], strlen($matches[0][0]));
        }

        // Search for <a href="mailto:|http(s)://mce_host/dir/email@amail.tld">text</a>
        while (preg_match($this->_getPattern('email', 'text'), $text, $matches, PREG_OFFSET_CAPTURE)) {
            $text = substr_replace($text, $this->_cloak($matches[1][0], $matches[2][0]), $matches[0][1],
                strlen($matches[0][0]));
        }

        // Search for <a href="mailto:|http(s)://mce_host/dir/email@amail.tld"><img ></a>
        while (preg_match($this->_getPattern('email', 'image'), $text, $matches, PREG_OFFSET_CAPTURE)) {
            $text = substr_replace($text, $this->_cloak($matches[1][0], $matches[2][0]), $matches[0][1],
                strlen($matches[0][0]));
        }

        //Search for <a  href="mailto:|http(s)://mce_host/dir/email@amail.tld?subject=subject">email@email.tld</a>
        while (preg_match($this->_getPattern(array('email', 'query'), 'email'), $text, $matches, PREG_OFFSET_CAPTURE)) {
            $text = substr_replace($text, $this->_cloak($matches[1][0] . $matches[2][0], $matches[1][0]),
                $matches[0][1],
                strlen($matches[0][0]));
        }

        // Search for <a  href="mailto:|http(s)://mce_host/dir/email@amail.tld?subject=subject">text</a>
        while (preg_match($this->_getPattern(array('email', 'query'), 'text'), $text, $matches, PREG_OFFSET_CAPTURE)) {
            $text = substr_replace($text, $this->_cloak($matches[1][0] . $matches[2][0], $matches[3][0]),
                $matches[0][1],
                strlen($matches[0][0]));
        }

        // Search for email@amail.tld
        $pattern = '~' . $this->_getRegExp('email') . '(\s+|$)~i';
        while (preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
            $text = substr_replace($text, $this->_cloak($matches[1][0]), $matches[1][1],
                strlen($matches[1][0]));
        }

        return $this;
    }

    /**
     * Simple Javascript email Cloaker
     *
     * By default replaces an email with a mailto link with email cloacked
     */
    protected function _cloak($email, $text = '') {
        // Random var suffix.
        $rand = rand(1, 100000);

        // Obfuscate inputs.
        $email = $this->_encode($email);

        // Split email address
        $email_parts = explode('@', $email);

        $output = "\n <script data-inline language='JavaScript' type='text/javascript'>";
        $output .= "\n <!--";
        $output .= "\n var path = 'hr' + 'ef' + '=';";
        $output .= "\n var addy{$rand} = '" . $email_parts[0] . "' + '&#64;';";
        $output .= "\n addy{$rand} = addy{$rand} + '";
        $output .= implode('\' + \'&#46;\' + \'', explode('.', $email_parts[1])) . '\';';

        if ($this->_linkable) {
            // Render a linkable email address.
            $output .= "\n document.write('<a ' + path + '\'mailto:' + addy{$rand} + '\'>');";
            if ($text) {
                if ($this->_isEmail($text)) {
                    $text       = $this->_encode($text);
                    $text_parts = explode('@', $text);
                    $output .= "\n var text{$rand} = '{$text_parts[0]}' + '&#64;';";
                    $output .= "\n text{$rand} = text{$rand} + '";
                    $output .= implode('\' + \'&#46;\' + \'', explode('.', $text_parts[1])) . '\';';
                } else {
                    $output .= "\n var text{$rand} = '{$text}';";
                }
                $output .= "\n document.write(text{$rand});";
            } else {
                // Use email address as link text.
                $output .= "\n document.write(addy{$rand});";
            }
            $output .= "\n document.write('</a>');";
        } else {
            // Do not link, just render the email address.
            $output .= "\n document.write( addy{$rand});";
        }
        $output .= "\n //-->";
        $output .= "\n </script>";

        // XHTML compliance `No JavaScript` text handling
        $output .= "<script data-inline language='JavaScript' type='text/javascript'>";
        $output .= "\n <!--";
        $output .= "\n document.write( '<span style=\'display: none;\'>' );";
        $output .= "\n //-->";
        $output .= "\n </script>";
        $output .= JText::_('CLOAKING');
        $output .= "\n <script data-inline language='JavaScript' type='text/javascript'>";
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
     *
     * @return bool True if email address, false otherwise.
     */
    protected function _isEmail($text) {
        $result  = false;
        $pattern = '~' . $this->_getRegExp('email') . '~i';
        if (preg_match($pattern, $text, $matches)) {
            $result = true;
        }
        return $result;
    }

    /**
     * Text encoder.
     *
     * @param string $text Text to encode.
     *
     * @return string Encoded text.
     */
    protected function _encode($text) {
        // Replace with HTML entities.
        return str_replace(array('a', 'e', 'i', 'o', 'u'), array('&#97;', '&#101;', '&#105;', '&#111;', '&#117;'),
            $text);
    }
}