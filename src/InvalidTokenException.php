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

use RuntimeException;
use Tobento\Service\Form\TokenizerInterface;
use Throwable;

/**
 * InvalidTokenException
 */
class InvalidTokenException extends RuntimeException
{             
    /**
     * Create a new InvalidTokenException.
     *
     * @param TokenizerInterface $tokenizer
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous     
     */
    public function __construct(
        protected TokenizerInterface $tokenizer,
        string $message,
        int $code = 0,
        null|Throwable $previous = null        
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the tokenizer.
     *
     * @return TokenizerInterface
     */
    public function tokenizer(): TokenizerInterface
    {
        return $this->tokenizer;
    }
}