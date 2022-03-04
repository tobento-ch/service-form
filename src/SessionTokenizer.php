<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Form;

use Tobento\Service\Session\SessionInterface;

/**
 * SessionTokenizer
 */
class SessionTokenizer implements TokenizerInterface
{    
    /**
     * @var array<string, string> The generated tokens.
     */        
    protected array $generated = [];
    
    /**
     * @var string Prefix for storage.
     */        
    protected string $prefix = '_tz_';
    
    /**
     * Create a new SessionTokenizer.
     *
     * @param SessionInterface $session
     * @param string $tokenName
     * @param string $tokenInputName
     */    
    public function __construct(
        protected SessionInterface $session,
        protected string $tokenName = 'csrf',
        protected string $tokenInputName = '_token'
    ) {}

    /**
     * Set the token name.
     *
     * @param string $tokenName
     * @return static $this
     */
    public function setTokenName(string $tokenName): static
    {
        $this->tokenName = $tokenName;
        
        return $this;
    }

    /**
     * Returns the token name.
     *
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }
    
    /**
     * Set the token input name.
     *
     * @param string $tokenInputName
     * @return static $this
     */
    public function setTokenInputName(string $tokenInputName): static
    {
        $this->tokenInputName = $tokenInputName;
        
        return $this;
    }

    /**
     * Returns the token input name.
     *
     * @return string
     */
    public function getTokenInputName(): string
    {
        return $this->tokenInputName;
    }
    
    /**
     * Returns the token for the specified name.
     *
     * @param string $name The token name.
     * @return null|string Null if token does not exist
     */
    public function get(string $name): null|string
    {
        $name = $this->prefixName($name);

        return $this->session->get($name);
    }

    /**
     * Generates and returns a new token for the specified name.
     * If the token has been generated, it should return it
     * and not generate a new one.
     *
     * @param string $name The token name.
     * @return string The token generated.
     */
    public function generate(string $name): string
    {
        $name = $this->prefixName($name);
        
        // generate once for same request:
        if (isset($this->generated[$name])) {
            return $this->generated[$name];
        }
        
        // one time token:
        if (!is_null($token = $this->session->get($name))) {
            return $token;
        }        
        
        $token = bin2hex(random_bytes(16));
        
        $this->session->set($name, $token);
            
        return $this->generated[$name] = $token;
    }

    /**
     * Delete the token for the specified name.
     *
     * @param string $name The token name.
     * @return static $this
     */
    public function delete(string $name): static
    {
        $name = $this->prefixName($name);
        
        $this->session->delete($name);
        
        return $this;
    }
    
    /**
     * Verifies token.
     *
     * @param mixed $inputToken The input token.
     * @param null|string $name The name of the token stored.
     * @param null|string $token The token to verify against input token.
     * @return bool True if token is valid, false token mismatch.
     */
    public function verifyToken(mixed $inputToken, null|string $name = null, null|string $token = null): bool
    {
        if (!is_string($inputToken)) {
            return false;
        }
    
        if (empty($inputToken)) {
            return false;
        }    
        
        if ($name !== null) {
            $token = $this->get($name);
        }
        
        return $inputToken === $token;
    }
    
    /**
     * Prefixes the name.
     *
     * @param string $name
     * @return string
     */
    protected function prefixName(string $name): string
    {
        return $this->prefix.$name;
    }
}