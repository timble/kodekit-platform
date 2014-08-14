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
 * Http Token Interface
 *
 * Using the JSON Web Token standard
 * @see http://tools.ietf.org/html/draft-ietf-oauth-json-web-token-06
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
interface HttpTokenInterface
{
    /**
     * Get the token type
     *
     * The value of the header parameter "typ" in the JWT header segment
     *
     * @return string
     */
    public function getType();

    /**
     * Set the token type
     *
     * The value of the header parameter typ is case sensitive and optional, and if present the recommended values are
     * either "JWT" or "http://openid.net/specs/jwt/1.0".
     *
     * @param string $type
     * @return HttpTokenInterface
     */
    public function setType($type);

    /**
     * Get the cryptographic algorithm used to secure the JWS.
     *
     * The value of the header parameter "alg" in the JWT header segment
     *
     * @return string
     */
    public function getAlgorithm();

    /**
     * Sets cryptographic algorithm used to secure the token.
     *
     * @param string $algorithm The signing algorithm. Supported algorithms are 'HS256', 'HS384' and 'HS512' or none
     * @throws \DomainException If an unsupported algorithm was specified
     * @return HttpTokenInterface
     */
    public function setAlgorithm($algorithm);

    /**
     * Get the token issuer
     *
     * The value of the claim parameter "iss" in the JWT claim segment
     *
     * @return string
     */
    public function getIssuer();

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
    public function setIssuer($issuer);

    /**
     * Get the token subject
     *
     * The value of the claim parameter "sub" in the JWT claim segment
     *
     * @return string
     */
    public function getSubject();

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
    public function setSubject($subject);

    /**
     * Get the expiration time of the token.
     *
     * The value of the claim parameter 'exp' as DateTime.
     *
     * @return \DateTime A \DateTime instance
     * @throws \RuntimeException If the data could not be parsed
     * @return \DateTime|null A DateTime instance or NULL if the token doesn't contain and expiration time
     */
    public function getExpireTime();

    /**
     * Sets the expiration time of the token.
     *
     * Sets the 'exp' claim in the JWT claim segment. This claim identifies the expiration time on or after which the
     * token MUST NOT be accepted for processing
     *
     * @param  \DateTime $date A DateTime instance
     * @return HttpTokenInterface
     */
    public function setExpireTime(\DateTime $date);

    /**
     * Get the issue time of the token.
     *
     * The value of the claim parameter 'iat' as DateTime.
     *
     * @return \DateTime A \DateTime instance
     * @throws \RuntimeException If the data could not be parsed
     * @return \DateTime|null A DateTime instance or NULL if the token doesn't contain and expiration time
     */
    public function getIssueTime();

    /**
     * Sets the issue time of the token.
     *
     * This method sets the 'iat' claim in the JWT claim segment. This claim identifies the UTC time at which the JWT
     * was issued.
     *
     * @param  \DateTime $date A DateTime instance
     * @return HttpTokenInterface
     */
    public function setIssueTime(\DateTime $date);

    /**
     * Get a claim
     *
     * @param string $name The name if the claim
     * @return mixed
     */
    public function getClaim($name);

    /**
     * Sets a claim of the current token
     *
     * @param string $name  The name if the claim
     * @param mixed  $value The value of the claim
     * @return HttpTokenInterface
     */
    public function setClaim($name, $value);

    /**
     * Returns the header of the token
     *
     * @return array
     */
    public function getHeader();

    /**
     * Get the token signature
     *
     * @param string|null $key  The secret key
     * @return
     */
    public function getSignature($secret = null);

    /**
     * Returns the age of the token
     *
     * @return integer|false The age of the token in seconds or FALSE if the age couldn't be calculated
     */
    public function getAge();

    /**
     * Encode to a JWT string
     *
     * This method returns the text representation of the name/value pair defined in the JWT token. First segment is
     * the name/value pairs of the header segment and the second segment is the collection of the name/value pair of
     * the claim segment.
     *
     * @return string  A serialised JWT token string
     */
    public function toString();

    /**
     * Decode from JWT string
     *
     * @param string      $token  A serialised token
     * @param string|null $key    The secret to be used to verify the HMAC signature bytes of the JWT token
     * @param bool        $verify Don't skip verification process
     * @return HttpTokenInterface
     * @throws \InvalidArgumentException If the token is invalid
     */
    public function fromString($token);

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
    public function verify($secret, $signed = false);

    /**
     * Sign the token
     *
     * This method returns the Base64url representation of the JWT token including the Crypto segment.
     *
     * @param mixed $secret The MAC key or password to be used to compute the HMAC signature bytes.
     * @return String the Base64url representation of the signed JWT token
     */
    public function sign($secret);

    /**
     * Checks whether the token is expired.
     *
     * @return bool
     */
    public function isExpired();
}