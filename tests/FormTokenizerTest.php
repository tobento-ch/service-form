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
use Tobento\Service\Form\Form;
use Tobento\Service\Form\SessionTokenizer;
use Tobento\Service\Session\Session;

/**
 * FormTokenizerTest
 */
class FormTokenizerTest extends TestCase
{
    public function testTokenizerMethod()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('ses'),
        );
        
        $form = new Form(
            tokenizer: $tokenizer,
        );
        
        $this->assertSame(
            $tokenizer,
            $form->tokenizer()
        ); 
    }
    
    public function testGenerateTokenMethod()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('ses'),
        );
        
        $form = new Form(
            tokenizer: $tokenizer,
        );
        
        $token = $form->generateToken();
        
        $this->assertSame(
            $tokenizer->get('csrf'),
            $token
        );      
        
        $tokenizer->setTokenName('foo');
        
        $token = $form->generateToken();
        
        $this->assertSame(
            $tokenizer->get('foo'),
            $token
        );        
    }
    
    public function testGenerateTokenMethodReturnsSameTokenIfExist()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('ses'),
        );
        
        $form = new Form(
            tokenizer: $tokenizer,
        );
        
        $token = $form->generateToken();
        
        $this->assertSame(
            $token,
            $form->generateToken()
        );      
    }
    
    public function testGenerateTokenInputMethod()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('ses'),
        );
        
        $form = new Form(
            tokenizer: $tokenizer,
        );
        
        $tokenInput = $form->generateTokenInput();
        
        $this->assertSame(
            '<input name="_token" type="hidden" value="'.$tokenizer->get('csrf').'">',
            $tokenInput
        );
        
        $tokenizer->setTokenInputName('_tk');
        $tokenInput = $form->generateTokenInput();
        
        $this->assertSame(
            '<input name="_tk" type="hidden" value="'.$tokenizer->get('csrf').'">',
            $tokenInput
        );        
    }
    
    public function testFormMethodAddsHiddenToken()
    {
        $tokenizer = new SessionTokenizer(
            session: new Session('ses'),
        );
        
        $form = new Form(
            tokenizer: $tokenizer,
        );
        
        $token = $form->generateToken();
        
        $this->assertSame(
            '<form method="POST"><input name="_token" type="hidden" value="'.$token.'">',
            $form->form()
        );      
    }    
}