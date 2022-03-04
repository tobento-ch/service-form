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

namespace Tobento\Service\Form\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tobento\Service\Form\TokenizerInterface;
use Tobento\Service\Form\InvalidTokenException;
use Tobento\Service\Requester\Requester;

/**
 * VerifyCsrfToken middleware.
 */
class VerifyCsrfToken implements MiddlewareInterface
{
    /**
     * The key used to get the uris to exclude from CSRF protection.
     */
    public const EXCLUDE_URIS_KEY = 'csrf_exclude_uris';
    
    /**
     * Create a new VerifyCsrfToken.
     *
     * @param TokenizerInterface $tokenizer
     * @param string $name
     * @param string $inputName
     * @param null|string $headerTokenName Null if not to allow header token
     * @param bool $onetimeToken
     */
    public function __construct(
        private TokenizerInterface $tokenizer,
        private string $name = 'csrf',
        private string $inputName = '_token',
        private null|string $headerTokenName = 'X-Csrf-Token',
        private bool $onetimeToken = false
    ) {
        $this->tokenizer->setTokenName($name);
        $this->tokenizer->setTokenInputName($inputName);
    }
    
    /**
     * Process the middleware.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->isReading($request) ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request))
        {
            return $handler->handle($request);
        }
        
        throw new InvalidTokenException($this->tokenizer, 'CSRF Token mismatch!');
    }

    /**
     * Determine if the HTTP request uses a 'read' verb.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    protected function isReading(ServerRequestInterface $request): bool
    {
        return in_array($request->getMethod(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Checks if tokens match.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    protected function tokensMatch(ServerRequestInterface $request): bool
    {
        $storedToken = $this->tokenizer->get($this->name);

        if ($this->onetimeToken) {
            $this->tokenizer->delete($this->name);
        }
        
        if (empty($storedToken)) {
            throw new InvalidTokenException(
                $this->tokenizer,
                'CSRF Token invalid! Token does not exist.'
            );
        }
        
        $headerToken = '';
        
        if ($this->headerTokenName) {
            $headerToken = $request->getHeaderLine($this->headerTokenName);
        }
        
        $inputToken = (new Requester($request))->input()->get($this->inputName, $headerToken);

        return $this->tokenizer->verifyToken($inputToken, null, $storedToken);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    protected function inExceptArray(ServerRequestInterface $request): bool
    {
        $uris = $request->getAttribute(self::EXCLUDE_URIS_KEY);
        
        if (!is_array($uris)) {
            $uris = [];
        }
        
        return in_array((string) $request->getUri(), $uris);
    }
}