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
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\Messages;

/**
 * FormMessagesTest
 */
class FormMessagesTest extends TestCase
{
    public function testMessagesMethod()
    {
        $form = new Form();
        
        $this->assertInstanceof(
            MessagesInterface::class,
            $form->messages()
        );
    }
    
    public function testMessagesReturnsSameInstance()
    {
        $messages = new Messages();
        
        $form = new Form(
            messages: $messages,
        );
        
        $this->assertSame(
            $messages,
            $form->messages()
        ); 
    }
    
    public function testWithMessagesMethodReturnsNewFormInstance()
    {
        $form = new Form();
        
        $newForm = $form->withMessages(new Messages());
        
        $this->assertFalse(
            $form === $newForm
        ); 
    }
    
    public function testWithMessagesMethodWithNull()
    {
        $form = new Form();
        
        $newForm = $form->withMessages(null);
        
        $this->assertInstanceof(
            MessagesInterface::class,
            $newForm->messages()
        );
    }
    
    public function testGetMessageMethod()
    {
        $form = new Form();
        
        $form->messages()->add(
            level: 'error',
            message: 'Error message',
            key: 'foo',
        );
        
        $form->messages()->add(
            level: 'Notice',
            message: 'Notice message',
            key: 'bar',
        );        
        
        $this->assertSame(
            '<span class="form-message error">Error message</span>',
            $form->getMessage('foo')
        );
        
        $this->assertSame(
            '<span class="form-message notice">Notice message</span>',
            $form->getMessage('bar')
        );        
        
        $this->assertSame(
            '',
            $form->getMessage('key')
        );        
    }
    
    public function testGetRenderedMessageKeysMethod()
    {
        $form = new Form();
        
        $form->messages()->add(
            level: 'error',
            message: 'Error message',
            key: 'foo',
        );
        
        $form->messages()->add(
            level: 'Notice',
            message: 'Notice message',
            key: 'bar',
        );
        
        $form->getMessage('foo');
        $form->getMessage('bar');
        $form->getMessage('key');    
        
        $this->assertSame(
            ['foo', 'bar'],
            $form->getRenderedMessageKeys()
        );        
    }    
}