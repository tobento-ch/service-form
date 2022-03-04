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
use Tobento\Service\Form\FormFactoryInterface;
use Tobento\Service\Form\ResponserFormFactory;
use Tobento\Service\Form\Form;
use Tobento\Service\Responser\ResponserInterface;
use Tobento\Service\Responser\Responser;
use Tobento\Service\Message\Messages;
use Tobento\Service\Form\SessionTokenizer;
use Tobento\Service\Session\Session;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * ResponserFormFactoryTest
 */
class ResponserFormFactoryTest extends TestCase
{
    public function testThatImplementsFormFactoryInterface()
    {
        $psr17Factory = new Psr17Factory();
        
        $responser = new Responser(
            responseFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );
        
        $formFactory = new ResponserFormFactory(
            responser: $responser,
            tokenizer: null,
        );
        
        $this->assertInstanceOf(
            FormFactoryInterface::class,
            $formFactory
        );
    }
    
    public function testCreateFormMethod()
    {
        $psr17Factory = new Psr17Factory();
        
        $responser = new Responser(
            responseFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );
        
        $formFactory = new ResponserFormFactory(
            responser: $responser,
        );
        
        $this->assertInstanceOf(
            Form::class,
            $formFactory->createForm()
        );
    }
    
    public function testInputDataIsPassedFromResponser()
    {
        $psr17Factory = new Psr17Factory();
        
        $responser = new Responser(
            responseFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );
        
        $formFactory = new ResponserFormFactory(
            responser: $responser->withInput(['color' => 'blue', 'options' => ['size' => ['S']]]),
        );
        
        $form = $formFactory->createForm();
        
        $this->assertSame(
            'blue',
            $form->getInput('color')
        );
        
        $this->assertSame(
            ['S'],
            $form->getInput('options.size')
        );
    }
    
    public function testMessagesArePassedFromResponser()
    {
        $psr17Factory = new Psr17Factory();
        
        $messages = new Messages();
        
        $responser = new Responser(
            responseFactory: $psr17Factory,
            streamFactory: $psr17Factory,
            messages: $messages,
        );
        
        $formFactory = new ResponserFormFactory(
            responser: $responser,
        );
        
        $form = $formFactory->createForm();
        
        $this->assertSame(
            $messages,
            $form->messages()
        );
    }
    
    public function testWithTokenizer()
    {
        $psr17Factory = new Psr17Factory();
        
        $responser = new Responser(
            responseFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );
        
        $tokenizer = new SessionTokenizer(
            session: new Session('sess'),
        );        
        
        $formFactory = new ResponserFormFactory(
            responser: $responser,
            tokenizer: $tokenizer,
        );
        
        $form = $formFactory->createForm();
        
        $this->assertSame(
            $tokenizer,
            $form->tokenizer()
        );
    }    
}