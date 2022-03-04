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

namespace Tobento\Service\Form\Test\Middleware;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Form\Middleware\VerifyCsrfToken;
use Tobento\Service\Form\InvalidTokenException;
use Psr\Http\Server\MiddlewareInterface;
use Tobento\Service\Form\SessionTokenizer;
use Tobento\Service\Session\Session;
use Tobento\Service\Middleware\MiddlewareDispatcher;
use Tobento\Service\Middleware\AutowiringMiddlewareFactory;
use Tobento\Service\Middleware\FallbackHandler;
use Tobento\Service\Container\Container;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * VerifyCsrfTokenTest
 */
class VerifyCsrfTokenTest extends TestCase
{
    public function testThatImplementsMiddlewareInterface()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $middleware = new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf',
            inputName: '_token',
            headerTokenName: 'X-Csrf-Token',
            onetimeToken: false,
        );
        
        $this->assertInstanceOf(
            MiddlewareInterface::class,
            $middleware
        );
    }
    
    public function testThrowsInvalidTokenExceptionIfNoTokenAtAll()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf',
            inputName: '_token'
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        try {
            $response = $dispatcher->handle($request);
        } catch (InvalidTokenException $e) {
            $this->assertTrue(true);
        }
    }
    
    public function testThrowsInvalidTokenExceptionIfTokenDoesNotMatch()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf',
            inputName: '_token'
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        $inputToken = $tokenizer->generate('csrf-foo');
        
        $request = $request->withParsedBody(['_token' => $inputToken]);
        
        try {
            $response = $dispatcher->handle($request);
        } catch (InvalidTokenException $e) {
            $this->assertTrue(true);
        }
    }
    
    public function testTokenMatches()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf',
            inputName: '_token'
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        $inputToken = $tokenizer->generate('csrf');
        
        $request = $request->withParsedBody(['_token' => $inputToken]);
        
        $response = $dispatcher->handle($request);
        
        $this->assertTrue(true);
    }
    
    public function testTokenMatchesWithHeader()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf',
            inputName: '_token',
            headerTokenName: 'X-Csrf-Token'
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        $inputToken = $tokenizer->generate('csrf');
        
        $request = $request->withHeader('X-Csrf-Token', $inputToken);
        
        $response = $dispatcher->handle($request);
        
        $this->assertTrue(true);
    }    
    
    public function testTokenMatchesWithDifferentNames()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf-login',
            inputName: '_token_login'
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        $inputToken = $tokenizer->generate('csrf-login');
        
        $request = $request->withParsedBody(['_token_login' => $inputToken]);
        
        $response = $dispatcher->handle($request);
        
        $this->assertTrue(true);
    }    
    
    public function testVerifyTokenIsIgnoredOnReadingMethods()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            name: 'csrf',
            inputName: '_token'
        ));
        
        $request = (new Psr17Factory())->createServerRequest('GET', 'https://example.com/login');        
        $response = $dispatcher->handle($request);
        
        $request = (new Psr17Factory())->createServerRequest('HEAD', 'https://example.com/login');        
        $response = $dispatcher->handle($request);
        
        $request = (new Psr17Factory())->createServerRequest('OPTIONS', 'https://example.com/login');        
        $response = $dispatcher->handle($request);        
        
        $this->assertTrue(true);
    }
        
    public function testOnetimeToken()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            onetimeToken: true
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        $inputToken = $tokenizer->generate('csrf');
        
        $request = $request->withParsedBody(['_token' => $inputToken]);
        
        $response = $dispatcher->handle($request);
        
        $this->assertTrue(true);
        
        try {
            $response = $dispatcher->handle($request);
        } catch (InvalidTokenException $e) {
            $this->assertTrue(true);
        }        
    }
    
    public function testExcludeUris()
    {
        // create middleware dispatcher.
        $dispatcher = new MiddlewareDispatcher(
            new FallbackHandler((new Psr17Factory())->createResponse(404)),
            new AutowiringMiddlewareFactory(new Container())
        );

        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $dispatcher->add(new VerifyCsrfToken(
            tokenizer: $tokenizer,
            onetimeToken: true
        ));

        $request = (new Psr17Factory())->createServerRequest('POST', 'https://example.com/login');
        
        $request = $request->withAttribute(
            VerifyCsrfToken::EXCLUDE_URIS_KEY,
            [
                'https://example.com/login',
            ]
        );
        
        $response = $dispatcher->handle($request);
        
        $this->assertTrue(true);
    }    
}