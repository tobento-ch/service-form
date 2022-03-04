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

/**
 * TokenizerInterface
 */
interface TokenizerInterface
{
    /**
     * Set the token name.
     *
     * @param string $tokenName
     * @return static $this
     */
    public function setTokenName(string $tokenName): static;

    /**
     * Returns the token name.
     *
     * @return string
     */
    public function getTokenName(): string;
    
    /**
     * Set the token input name.
     *
     * @param string $tokenInputName
     * @return static $this
     */
    public function setTokenInputName(string $tokenInputName): static;

    /**
     * Returns the token input name.
     *
     * @return string
     */
    public function getTokenInputName(): string;   
    
    /**
     * Returns the token for the specified name.
     *
     * @param string $name The token name.
     * @return null|string Null if token does not exist
     */
    public function get(string $name): null|string;

    /**
     * Generates and returns a new token for the specified name.
     * If the token has been generated, it should return it
     * and not generate a new one.
     *
     * @param string $name The token name.
     * @return string The token generated.
     */
    public function generate(string $name): string;

    /**
     * Delete the token for the specified name.
     *
     * @param string $name The token name.
     * @return static $this
     */
    public function delete(string $name): static;
    
    /**
     * Verifies token.
     *
     * @param mixed $inputToken The input token.
     * @param null|string $name The name of the token stored.
     * @param null|string $token The token to verify against input token.
     * @return bool True if token is valid, false token mismatch.
     */
    public function verifyToken(mixed $inputToken, null|string $name = null, null|string $token = null): bool;
}