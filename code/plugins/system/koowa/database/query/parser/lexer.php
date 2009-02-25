<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright	Copyright (C) 2008 Joomlatools. All rights reserved.
 * @license		GNU LGPL <http://www.gnu.org/licenses/lgpl.html>
 */

/**
 * A lexigraphical analyser inspired by the msql lexer
 *
 * This class draws heavily on PEAR:SQL_Parser Copyright (c) 2002-2004 Brent Cook,
 * released under the LGPL license
 *
 * @author     	Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQueryParserLexer
{
    // array of valid tokens for the lexer to recognize
    // format is 'token literal'=>TOKEN_VALUE
    var $symbols = array();

    // {{{ instance variables
    var $tokPtr = 0;
    var $tokStart = 0;
    var $tokLen = 0;
    var $tokText = '';
    var $lineNo = 0;
    var $lineBegin = 0;
    var $string = '';
    var $stringLen = 0;

    // Will not be altered by skip()
    var $tokAbsStart = 0;
    var $skipText = '';

    // Provide lookahead capability.
    var $lookahead = 0;
    // Specify how many tokens to save in tokenStack, so the
    // token stream can be pushed back.
    var $tokenStack = array();
    var $stackPtr = 0;

    public function __construct($string = '', $lookahead = 0, $lexeropts)
    {
        $this->string = $string;
        $this->stringLen = strlen($string);
        $this->lookahead = $lookahead;
        $this->allowIdentFirstDigit = $lexeropts['allowIdentFirstDigit'];
    }

    public function get()
    {
        ++$this->tokPtr;
        ++$this->tokLen;
        return ($this->tokPtr <= $this->stringLen) ? $this->string{$this->tokPtr - 1} : null;
    }

    public function unget()
    {
        --$this->tokPtr;
        --$this->tokLen;
    }

    public function skip()
    {
        ++$this->tokStart;
        return ($this->tokPtr != $this->stringLen) ? $this->string{$this->tokPtr++} : null;
    }

    public function revert()
    {
        $this->tokPtr = $this->tokStart;
        $this->tokLen = 0;
    }

    public function isCompop($c)
    {
        return (($c == '<') || ($c == '>') || ($c == '=') || ($c == '!'));
    }

    /*
     * Push back a token, so the very next call to lex() will return that token.
     * Calls to this function will be ignored if there is no lookahead specified
     * to the constructor, or the pushBack() function has already been called the
     * maximum number of token's that can be looked ahead.
     */
    public function pushBack()
    {
        if ($this->lookahead > 0 && count($this->tokenStack) > 0 && $this->stackPtr > 0) {
            $this->stackPtr--;
        }
    }

    public function lex()
    {
        if ($this->lookahead > 0) {
            // The stackPtr, should always be the same as the count of
            // elements in the tokenStack.  The stackPtr, can be thought
            // of as pointing to the next token to be added.  If however
            // a pushBack() call is made, the stackPtr, will be less than the
            // count, to indicate that we should take that token from the
            // stack, instead of calling nextToken for a new token.

            if ($this->stackPtr < count($this->tokenStack)) {

                $this->tokText  = $this->tokenStack[$this->stackPtr]['tokText'];
                $this->skipText = $this->tokenStack[$this->stackPtr]['skipText'];
                $token = $this->tokenStack[$this->stackPtr]['token'];

                // We have read the token, so now iterate again.
                $this->stackPtr++;
                return $token;

            } else {

                // If $tokenStack is full (equal to lookahead), pop the oldest
                // element off, to make room for the new one.

                if ($this->stackPtr == $this->lookahead) {
                    // For some reason array_shift and
                    // array_pop screw up the indexing, so we do it manually.
                    for ($i = 0; $i < (count($this->tokenStack) - 1); $i++) {
                        $this->tokenStack[$i] = $this->tokenStack[$i + 1];
                    }

                    // Indicate that we should put the element in
                    // at the stackPtr position.
                    $this->stackPtr--;
                }

                $token = $this->nextToken();
                $this->tokenStack[$this->stackPtr] =
                    array('token'=>$token,
                            'tokText'=>$this->tokText,
                            'skipText'=>$this->skipText);
                $this->stackPtr++;
                return $token;
            }
        } else {
            return $this->nextToken();
        }
    }

    public function nextToken()
    {
        //echo 'last token: ' . $this->tokText . "\n";
        if ($this->string == '') {
            return;
        }
        $state = 0;
        $this->tokAbsStart = $this->tokStart;

        while (true) {
            //echo "State: $state, Char: $c\n";
            switch ($state) {
                // {{{ State 0 : Start of token
                case 0:
                    $this->tokPtr = $this->tokStart;
                    $this->tokText = '';
                    $this->tokLen = 0;
                    $c = $this->get();

                    if (is_null($c)) { // End Of Input
                        $state = 1000;
                        break;
                    }

                    while (($c == ' ') || ($c == "\t")
                            || ($c == "\n") || ($c == "\r")
                    ) {
                        if ($c == "\n" || $c == "\r") {
                            // Handle MAC/Unix/Windows line endings.
                            if ($c == "\r") {
                                $c = $this->skip();

                                // If not DOS newline
                                if ($c != "\n") {
                                    $this->unget();
                                }
                            }
                            ++$this->lineNo;
                            $this->lineBegin = $this->tokPtr;
                        }

                        $c = $this->skip();
                        $this->tokLen = 1;
                    }

                    // Escape quotes and backslashes
                    if ($c == '\\') {
                        $t = $this->get();
                        if ($t == '\'' || $t == '\\' || $t == '"') {
                            $this->tokText = $t;
                            $this->tokStart = $this->tokPtr;
                            return $this->tokText;
                        } else {
                            $this->unget();

                            // Unknown token.  Revert to single char
                            $state = 999;
                            break;
                        }
                    }

                    if (isset($this->quotes[$c])) {
                        $quote = $c;
                        $state = 12;
                        break;
                    }

                    if ($c == '_') { // system variable
                        $state = 18;
                        break;
                    }

                    if (ctype_alpha(ord($c))) { // keyword or ident
                        $state = 1;
                        break;
                    }

                    if (ctype_digit(ord($c))) { // real or int number
                        $state = 5;
                        break;
                    }

                    if ($c == '.') {
                        $t = $this->get();
                        if ($t == '.') { // ellipsis
                            if ($this->get() == '.') {
                                $this->tokText = '...';
                                $this->tokStart = $this->tokPtr;
                                return $this->tokText;
                            } else {
                                $state = 999;
                                break;
                            }
                        } else if (ctype_digit(ord($t))) { // real number
                            $this->unget();
                            $state = 7;
                            break;
                        } else { // period
                            $this->unget();
                        }
                    }


                    // comments
                    foreach ($this->comments as $comment_start => $comment_end) {
                        if (substr($this->string, $this->tokPtr - 1, strlen($comment_start)) === $comment_start) {
                            $state = 14;
                            break 2;
                        }
                    }

                    if ($c == '-') {
                        // negative number
                        $state = 5;
                        break;
                    }

                    if ($this->isCompop($c)) { // comparison operator
                        $state = 10;
                        break;
                    }
                    // Unknown token.  Revert to single char
                    $state = 999;
                    break;

                    // Incomplete keyword or ident
                case 1:
                    $c = $this->get();
                    if (ctype_alnum(ord($c)) || ($c == '_')) {
                        $state = 1;
                        break;
                    }
                    $state = 2;
                    break;

                    /* Complete keyword or ident */
                case 2:
                    $this->unget();
                    $this->tokText = substr($this->string, $this->tokStart,
                            $this->tokLen);

                    $testToken = strtolower($this->tokText);
                    if (isset($this->symbols[$testToken])) {

                        $this->skipText = substr($this->string, $this->tokAbsStart,
                                $this->tokStart-$this->tokAbsStart);
                        $this->tokStart = $this->tokPtr;
                        return $testToken;
                    } else {
                        $this->skipText = substr($this->string, $this->tokAbsStart,
                                $this->tokStart-$this->tokAbsStart);
                        $this->tokStart = $this->tokPtr;
                        return 'ident';
                    }
                    break;

                    // Incomplete real or int number
                case 5:
                    $c = $this->get();
                    if (ctype_digit(ord($c))) {
                        $state = 5;
                        break;
                    } else if ($c == '.') {
                        $t = $this->get();
                        if($t == '.') { // ellipsis
                            $this->unget();
                        } else { // real number
                            $state = 7;
                            break;
                        }
                    } else if(ctype_alpha(ord($c))) {
                        // Do we allow idents to begin with a digit?
                        if ($this->allowIdentFirstDigit) {
                            $state = 1;
                        } else { // a number must end with non-alpha character
                            $state = 999;
                        }
                        break;
                    } else {
                        // complete number
                        $state = 6;
                        break;
                    }

                    // Complete integer number
                case 6:
                    $this->unget();
                    $this->tokText = intval(substr($this->string, $this->tokStart,
                                $this->tokLen));
                    $this->skipText = substr($this->string, $this->tokAbsStart,
                            $this->tokStart-$this->tokAbsStart);
                    $this->tokStart = $this->tokPtr;
                    return 'int_val';
                    break;

                    // Incomplete real number
                case 7:
                    $c = $this->get();

                    if ($c == 'e' || $c == 'E') {
                        $state = 15;
                        break;
                    }

                    if (ctype_digit(ord($c))) {
                        $state = 7;
                        break;
                    }
                    $state = 8;
                    break;

                    // Complete real number
                case 8:
                    $this->unget();
                    $this->tokText = floatval(substr($this->string, $this->tokStart,
                                $this->tokLen));
                    $this->skipText = substr($this->string, $this->tokAbsStart,
                            $this->tokStart-$this->tokAbsStart);
                    $this->tokStart = $this->tokPtr;
                    return 'real_val';

                    // Incomplete comparison operator
                case 10:
                    $c = $this->get();
                    if ($this->isCompop($c)) {
                        $state = 10;
                        break;
                    }
                    $state = 11;
                    break;

                    // Complete comparison operator
                case 11:
                    $this->unget();
                    $this->tokText = substr($this->string, $this->tokStart,
                            $this->tokLen);
                    if ($this->tokText) {
                        $this->skipText = substr($this->string, $this->tokAbsStart,
                                $this->tokStart-$this->tokAbsStart);
                        $this->tokStart = $this->tokPtr;
                        return $this->tokText;
                    }
                    $state = 999;
                    break;

                    // Incomplete quoted string or ident
                case 12:
                    $bail = false;
                    while (! $bail) {
                        switch ($this->get()) {
                            case '':
                                $this->tokText = null;
                                $bail = true;
                                break;
                            case "\\":
                                if (! $this->get()) {
                                    $this->tokText = null;
                                    $bail = true;
                                }
                                //$bail = true;
                                break;
                            case $quote:
                                if ($quote != $this->get()) {
                                    $this->unget();
                                    $this->tokText = stripslashes(
                                        substr($this->string, $this->tokStart + 1,
                                            $this->tokLen - 2));
                                    $bail = true;
                                    break;
                                }
                        }
                    }
                    if (! is_null($this->tokText)) {
                        $state = 13;
                        break;
                    }
                    $state = 999;
                    break;

                    // Complete quoted string or ident
                case 13:
                    $this->skipText = substr($this->string, $this->tokAbsStart,
                            $this->tokStart - $this->tokAbsStart);
                    $this->tokStart = $this->tokPtr;
                    switch ($this->quotes[$quote]) {
                        case 'ident' :
                            return 'ident';
                            break;
                        case 'string' :
                        default :
                            return 'text_val';
                            break;
                    }
                    break;

                    // Comment
                case 14:
                    $c = $this->skip();
                    if (null === $c
                     || substr($this->string, $this->tokPtr, strlen($comment_end)) === $comment_end) {
                        $this->tokPtr += strlen($comment_end);
                        $this->tokStart = $this->tokPtr;
                        $this->tokLen = 0;
                        $state = 0;
                    } else {
                        $state = 14;
                    }
                    if ($c == "\n") {
                        ++$this->lineNo;
                        $this->lineBegin = $this->tokPtr;
                    }
                    break;

                    // Exponent Sign in Scientific Notation
                case 15:
                    $c = $this->get();
                    if($c == '-' || $c == '+') {
                        $state = 16;
                        break;
                    }
                    $state = 999;
                    break;

                    // Exponent Value-first digit in Scientific Notation
                case 16:
                    $c = $this->get();
                    if (ctype_digit(ord($c))) {
                        $state = 17;
                        break;
                    }
                    $state = 999;  // if no digit, then token is unknown
                    break;

                    // Exponent Value in Scientific Notation
                case 17:
                    $c = $this->get();
                    if (ctype_digit(ord($c))) {
                        $state = 17;
                        break;
                    }
                    $state = 8;  // At least 1 exponent digit was required
                    break;

                    // Incomplete System Variable
                case 18:
                    $c = $this->get();
                    if (ctype_alnum(ord($c)) || $c == '_') {
                        $state = 18;
                        break;
                    }
                    $state = 19;
                    break;

                    // Complete Sys Var
                case 19:
                    $this->unget();
                    $this->tokText = substr($this->string, $this->tokStart,
                            $this->tokLen);
                    $this->skipText = substr($this->string, $this->tokAbsStart,
                            $this->tokStart-$this->tokAbsStart);
                    $this->tokStart = $this->tokPtr;
                    return 'sys_var';

                    //Unknown token.  Revert to single char
                case 999:
                    $this->revert();
                    $this->tokText = $this->get();
                    $this->skipText = substr($this->string, $this->tokAbsStart,
                            $this->tokStart-$this->tokAbsStart);
                    $this->tokStart = $this->tokPtr;
                    return $this->tokText;
                    // }}}

                    //End Of Input
                case 1000:
                    $this->tokText = '*end of input*';
                    $this->skipText = substr($this->string, $this->tokAbsStart,
                            $this->tokStart-$this->tokAbsStart);
                    $this->tokStart = $this->tokPtr;
                    return null;
            }
        }
    }

}