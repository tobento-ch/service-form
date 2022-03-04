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

namespace Tobento\Service\Form\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Form\TokenizerInterface;
use Tobento\Service\Form\SessionTokenizer;
use Tobento\Service\Session\SessionInterface;
use Tobento\Service\Session\Session;

/**
 * SessionTokenizerTest
 */
class SessionTokenizerTest extends TestCase
{
    public function testThatImplementsTokenizerInterface()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
            tokenName: 'csrf',
            tokenInputName: '_token'
        );
        
        $this->assertInstanceOf(
            TokenizerInterface::class,
            $tokenizer
        );
    }
    
    public function testTokenNameMethods()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
            tokenName: 'csrf',
            tokenInputName: '_token'
        );
        
        $this->assertSame('csrf', $tokenizer->getTokenName());
        
        $this->assertSame($tokenizer, $tokenizer->setTokenName('foo'));
        
        $this->assertSame('foo', $tokenizer->getTokenName());
    }
    
    public function testTokenInputNameMethods()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
            tokenName: 'csrf',
            tokenInputName: '_token'
        );
        
        $this->assertSame('_token', $tokenizer->getTokenInputName());
        
        $this->assertSame($tokenizer, $tokenizer->setTokenInputName('foo'));
        
        $this->assertSame('foo', $tokenizer->getTokenInputName());
    }
    
    public function testGetMethodReturnsNullIfNotExists()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('ses'),
        );
        
        $this->assertSame(null, $tokenizer->get('bar'));
    }
    
    public function testGenerateAndGetMethod()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $token = $tokenizer->generate('csrf');
        
        $this->assertSame($token, $tokenizer->get('csrf'));
        
        $token = $tokenizer->generate('foo');
        
        $this->assertSame($token, $tokenizer->get('foo'));
    }
    
    public function testGenerateReturnsExistingAndDoesNotGenerateNew()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $token = $tokenizer->generate('csrf');
        
        $this->assertSame($token, $tokenizer->generate('csrf'));
    }    
    
    public function testDeleteMethod()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $token = $tokenizer->generate('csrf');
        
        $this->assertSame($token, $tokenizer->get('csrf'));
        
        $this->assertSame($tokenizer, $tokenizer->delete('csrf'));
        
        $this->assertSame(null, $tokenizer->get('csrf'));
    }
    
    public function testVerifyTokenMethodWithName()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $token = $tokenizer->generate('csrf');
        
        $this->assertTrue(
            $tokenizer->verifyToken(
                inputToken: $token,
                name: 'csrf',
            )
        );
        
        $this->assertFalse(
            $tokenizer->verifyToken(
                inputToken: 'foo',
                name: 'csrf',
            )
        );        
    }
    
    public function testVerifyTokenMethodWithToken()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );
        
        $token = $tokenizer->generate('csrf');
        
        $this->assertTrue(
            $tokenizer->verifyToken(
                inputToken: $token,
                token: $tokenizer->generate('csrf'),
            )
        );
        
        $this->assertFalse(
            $tokenizer->verifyToken(
                inputToken: 'foo',
                token: 'bar',
            )
        );        
    }    
}