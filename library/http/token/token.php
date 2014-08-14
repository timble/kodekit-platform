<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Http Token
 *
 * This Class represents the JSON Web Token (JWT). The claims in a JWT are encoded as a JSON object that is digitally
 * signed using a JSON Web Signature (JWS). Support for JSON Web Encryption (JWE) is not provided yet.
 *
 * JWT token contains three Segments: the JWT Header Segment, the JWT Claim Segment, and the JWT Signature Segment, in
 * that order, with the segments being separated by period ('.') characters. All the three Segments are always
 * Base64url encoded values.
 *
 * By default tokens expire 24 hours after they have been issued. To change this set a different expire time.
 *
 * @see http://tools.ietf.org/html/draft-ietf-oauth-json-web-token-06
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpToken extends Object implements HttpTokenInterface
{
    /**
     * The algorithm constants
     *
     * @see toString()
     */
    const HS256 = 'HS256';
    const HS384 = 'HS384';
    const HS512 = 'HS512';

    /**
     * The token claims
     *
     * @var array
     */
    protected $_claims;

    /**
     * The token header
     *
     * @var array
     */
    protected $_header;

    /**
     * The token signature
     *
     * @var array
     */
    protected $_signature;

    /**
     * The hashing algorithm
     *
     * @var string
     */
    protected $_algorithm;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional KObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the token type
        $this->setType($config->type);

        //Set the token header
        $this->setAlgorithm($config->algorithm);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation
     *
     * @param   ObjectConfig $config  An optional KObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'type'      => 'JWT' ,
            'algorithm' => self::HS256
        ));

        parent::_initialize($config);
    }

    /**
     * Get the token type
     *
     * The value of the header parameter "typ" in the JWT header segment
     *
     * @return string
     */
    public function getType()
    {
        return $this->_header['type'];
    }

    /**
     * Set the token type
     *
     * The value of the header parameter typ is case sensitive and optional, and if present the recommended values are
     * either "JWT" or "http://openid.net/specs/jwt/1.0".
     *
     * @param string $type
     * @return HttpTokenInterface
     */
    public function setType($type)
    {
        $this->_header['type'] = $type;
        return $this;
    }

    /**
     * Get the cryptographic algorithm used to secure the JWS.
     *
     * The value of the header parameter "alg" in the JWT header segment
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->_header['alg'];
    }

    /**
     * Sets cryptographic algorithm used to secure the token.
     *
     * @param string $algorithm The signing algorithm. Supported algorithms are 'HS256', 'HS384' and 'HS512' or none
     * @throws \DomainException If an unsupported algorithm was specified
     * @return HttpTokenInterface
     */
    public function setAlgorithm($algorithm)
    {
        $algorithms = array(
            'HS256' => 'sha256',
            'HS384' => 'sha384',
            'HS512' => 'sha512',
            'none'  => false
        );

        if (!isset($algorithms[$algorithm])) {
            throw new \DomainException('Algorithm not supported');
        }

        $this->_header['alg'] = $algorithm;
        $this->_algorithm     = $algorithms[$algorithm];

        return $this;
    }

    /**
     * Get the token issuer
     *
     * The value of the claim parameter "iss" in the JWT claim segment
     *
     * @return string
     */
    public function getIssuer()
    {
        $result = '';
        if(isset($this->_claims['iss'])) {
            $result = $this->_claims['iss'];
        }

        return $result;
    }

    /**
     * Set the token issuer
     *
     * This method sets the 'iss' (issuer) claim in the JWT claim segment. This claim identifies the principal that
     * issued the JWT. The processing of this claim is generally application specific. The "iss" value is a case
     * sensitive string containing a String Or URI value.  Use of this claim is OPTIONAL.
     *
     * @param string $issuer
     * @return HttpTokenInterface
     */
    public function setIssuer($issuer)
    {
        $this->_claims['iss'] = $issuer;
        return $this;
    }

    /**
     * Get the token subject
     *
     * The value of the claim parameter "sub" in the JWT claim segment
     *
     * @return string
     */
    public function getSubject()
    {
        $result = '';
        if(isset($this->_claims['sub'])) {
            $result = $this->_claims['sub'];
        }

        return $result;
    }

    /**
     * Set the token subject
     *
     * This method sets the 'sub' (subject) claim in the JWT claim segment. This claim identifies the subject that
     * issued the JWT. The Claims in a JWT are normally statements about the subject.  The processing of this claim
     * is generally application specific.  The "sub" value is a case sensitive string containing a String or URI value.
     * Use of this claim is OPTIONAL.
     *
     * @param string $subject
     * @return HttpTokenInterface
     */
    public function setSubject($subject)
    {
        $this->_claims['sub'] = $subject;
        return $this;
    }

    /**
     * Get the expiration time of the token.
     *
     * The value of the claim parameter 'exp' as DateTime.
     *
     * @return \DateTime A \DateTime instance
     * @throws \RuntimeException If the data could not be parsed
     * @return \DateTime|null A DateTime instance or NULL if the token doesn't contain and expiration time
     */
    public function getExpireTime()
    {
        $date = null;
        if(isset($this->_claims['exp']))
        {
            $value = $this->_claims['exp'];
            $date   = \DateTime::createFromFormat('U', $value);

            if ($date === false) {
                throw new \RuntimeException(sprintf('The token expire time is not parseable (%s).', $value));
            }
        };

        return $date;
    }

    /**
     * Sets the expiration time of the token.
     *
     * Sets the 'exp' claim in the JWT claim segment. This claim identifies the expiration time on or after which the
     * token MUST NOT be accepted for processing
     *
     * @param  \DateTime $date A DateTime instance
     * @return HttpResponseInterface
     */
    public function setExpireTime(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->_claims['exp'] = $date->format('U');

        return $this;
    }

    /**
     * Get the issue time of the token.
     *
     * The value of the claim parameter 'iat' as DateTime.
     *
     * @return \DateTime A \DateTime instance
     * @throws \RuntimeException If the data could not be parsed
     * @return \DateTime|null A DateTime instance or NULL if the token doesn't contain and expiration time
     */
    public function getIssueTime()
    {
        $date = null;
        if(isset($this->_claims['iat']))
        {
            $value = $this->_claims['iat'];
            $date  = \DateTime::createFromFormat('U', $value);

            if ($date === false) {
                throw new \RuntimeException(sprintf('The token issue time is not parseable (%s).', $value));
            }
        };

        return $date;
    }

    /**
     * Sets the issue time of the token.
     *
     * This method sets the 'iat' claim in the JWT claim segment. This claim identifies the UTC time at which the JWT
     * was issued.
     *
     * @param  \DateTime $date A DateTime instance
     * @return HttpTokenInterface
     */
    public function setIssueTime(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->_claims['iat'] = $date->format('U');

        return $this;
    }

    /**
     * Get a claim
     *
     * @param string $name The name if the claim
     * @return mixed
     */
    public function getClaim($name)
    {
        $result = null;
        if(isset($this->_claims[$name])) {
            $result = $this->_claims[$name];
        }

        return $result;
    }

    /**
     * Sets a claim of the current token
     *
     * @param string $name  The name if the claim
     * @param mixed  $value The value of the claim
     * @return HttpToken
     */
    public function setClaim($name, $value)
    {
        $this->_claims[$name] = $value;
        return $this;
    }

    /**
     * Returns the header of the token
     *
     * @return array
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * Get the token signature
     *
     * @param string|null $key  The secret key
     * @return
     */
    public function getSignature($secret = null)
    {
        $signature = '';

        if($this->_algorithm)
        {
            $header  = $this->_toBase64url($this->_toJson($this->_header));
            $payload = $this->_tobase64url($this->_toJson($this->_claims));

            $message   = sprintf("%s.%s", $header, $payload);
            $signature = hash_hmac($this->_algorithm, $message, $secret, true);
        }

        return $signature;
    }

    /**
     * Returns the age of the token
     *
     * @return integer|false The age of the token in seconds or FALSE if the age couldn't be calculated
     */
    public function getAge()
    {
        $result = false;
        if (isset($this->_claims['iat']) && is_numeric($this->_claims['iat'])) {
            $result = max(time() - $this->_claims['iat'], 0);
        }

        return $result;
    }

    /**
     * Encode to a JWT string
     *
     * This method returns the text representation of the name/value pair defined in the JWT token. First segment is
     * the name/value pairs of the header segment and the second segment is the collection of the name/value pair of
     * the claim segment.
     *
     * By default tokens expire 24 hours after they have been issued. To change this set a different expire time.
     *
     * @return string  A serialised JWT token string
     */
    public function toString()
    {
        $date = new \DateTime('now');

        //Make sure we have an issue time
        if (!isset($this->_claims['iat'])) {
            $this->setIssueTime($date);
        }

        if (!isset($this->_claims['exp'])){
            $this->setExpireTime($date->modify('+24 hours'));
        }

        $header    = $this->_toBase64url($this->_toJson($this->_header));
        $payload   = $this->_toBase64url($this->_toJson($this->_claims));

        return sprintf("%s.%s", $header, $payload);
    }

    /**
     * Decode from JWT string
     *
     * @param string      $token  A serialised token
     * @param string|null $key    The secret key
     * @param bool        $verify Don't skip verification process
     * @return HttpTokenInterface
     * @throws \InvalidArgumentException If the token is invalid
     */
    public function fromString($token)
    {
        $segments = explode('.', $token);

        if (count($segments) == 3)
        {
            list($header, $payload, $signature) = $segments;

            $this->_header    = $this->_fromJson($this->_fromBase64url($header));
            $this->_claims    = $this->_fromJson($this->_fromBase64url($payload));
            $this->_signature = $this->_fromBase64url($signature);

            if(isset($this->_header['alg'])) {
                $this->setAlgorithm($this->_header['alg']);
            } else {
                $this->setAlgorithm('none');
            }
        }
        else throw new \InvalidArgumentException(sprintf('The token "%s" is an invalid JWT', $token));

        return $this;
    }

    /**
     * Verify the token
     *
     * This method is used to verify the digitally signed JWT token. It does nothing, if the token is not signed
     * (i.e., the crypto segment of the JWT token is an empty string).
     *
     * @param mixed   $secret  The secret to be used to verify the HMAC signature bytes of the JWT token
     * @param boolean $signed  Ensure the token is signed. If FALSE, unsigned tokens will pass verification
     * @return bool  Returns TRUE if the signature of the JWT token is valid, FALSE otherwise.
     */
    public function verify($secret, $signed = true)
    {
        //An unsigned JWT is using the "none" "alg" header parameter value; and an empty string for its signature.
        if(!$signed && empty($this->_signature) && $this->getAlgorithm() == 'none') {
            return true;
        }

        //Verify the signature
        if ($this->_signature !== $this->getSignature($secret)) {
            return false;
        }

        return true;
    }

    /**
     * Sign the token
     *
     * This method returns the Base64url representation of the JWT token including the Crypto segment.
     *
     * @param mixed $secret The MAC key or password to be used to compute the HMAC signature bytes.
     * @return String the Base64url representation of the signed JWT token
     */
    public function sign($secret)
    {
        $token     = $this->toString();
        $signature = $this->getSignature($secret);

        return sprintf("%s.%s", $token, $this->_toBase64url($signature));
    }

    /**
     * Checks whether the token is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        if (isset($this->_claims['exp']) && is_numeric($this->_claims['exp']))
        {
            $now  = new \DateTime('now');
            return ($now->format('U') - $this->_claims['exp']) > 0;
        }

        return false;
    }

    /**
     * Encode a PHP object into a JSON string.
     *
     * @param object|array $input A PHP object or array
     * @return string JSON representation of the PHP object or array
     * @throws \DomainException Provided object could not be encoded to valid JSON
     */
    protected function _toJson($input)
    {
        $json = json_encode($input);

        if($json === false) {
            throw new \DomainException('Error encoding JSON data');
        }

        return $json;
    }

    /**
     * Decode a JSON string into a PHP object.
     *
     * @param string $input JSON string
     * @return array Array representation of JSON string
     * @throws \DomainException Provided string was invalid JSON
     */
    protected function _fromJson($input)
    {
        $obj = json_decode($input, true);

        if($obj === false) {
            throw new \DomainException('Error decoding JSON data');
        }

        return $obj;
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     * @return string The base64 encode of what you passed in
     */
    protected function _toBase64url($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     * @return string A decoded string
     */
    protected function _fromBase64url($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder)
        {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Allow PHP casting of this object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}