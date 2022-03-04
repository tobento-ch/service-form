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
use Tobento\Service\Form\Input;

/**
 * FormInputMethodRadioTest
 */
class FormInputMethodRadioTest extends TestCase
{
    public function testRadio()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
            )
        );
    }
    
    public function testSelectedAttributeWithString()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                selected: 'blue',
            )
        );
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red" checked>',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                selected: 'red',
            )
        );        
    }
    
    public function testSelectedAttributeWithArray()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                selected: ['blue', 'yellow'],
            )
        );
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red" checked>',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                selected: ['red', 'yellow'],
            )
        );        
    }  
    
    public function testInputData()
    {
        $form = new Form(
            input: new Input([
                'color' => 'red',
                'options' => [
                    'size' => 'large',
                ],
            ]),
        );

        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red" checked>',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
            )
        );
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                withInput: false,
            )
        );        
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="blue">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'blue',
            )
        );        
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red" checked>',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                selected: ['red', 'yellow'],
            )
        );
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="blue">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'blue',
                selected: 'red',
            )
        );        
        
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="red" checked>',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
                selected: ['blue', 'yellow'],
            )
        );
        
        $this->assertSame(
            '<input name="options[size]" id="options_size" type="radio" value="large" checked>',
            $form->input(
                name: 'options[size]',
                type: 'radio',
                value: 'large',
                selected: 'small',
                withInput: true,
            )
        );
        
        $this->assertSame(
            '<input name="options[size]" id="options_size" type="radio" value="large" checked>',
            $form->input(
                name: 'options.size',
                type: 'radio',
                value: 'large',
                selected: 'small',
                withInput: true,
            )
        );        
    }
    
    public function testMessages()
    {
        $form = new Form();
        
        $form->messages()->add(
            level: 'error',
            message: 'Error color message.',
            key: 'color',
        );
        
        $form->messages()->add(
            level: 'error',
            message: 'Error size message.',
            key: 'options.size',
        );
        
        $this->assertSame(
            '<span class="form-message error">Error color message.</span><input name="color" id="color" type="radio" value="red">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
            )
        );
                
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><input name="options[size]" id="options_size" type="radio" value="red">',
            $form->input(
                name: 'options[size]',
                type: 'radio',
                value: 'red',
            )
        );
        
        $form = new Form();
        
        $form->messages()->add(
            level: 'error',
            message: 'Error size message.',
            key: 'options.size',
        );        
        
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><input name="options[size]" id="options_size" type="radio" value="red">',
            $form->input(
                name: 'options.size',
                type: 'radio',
                value: 'red',
            )
        );
    }
    
    public function testMessagesRenderOnceIfSameName()
    {
        $form = new Form();
        
        $form->messages()->add(
            level: 'error',
            message: 'Error color message.',
            key: 'color',
        );
        
        $this->assertSame(
            '<span class="form-message error">Error color message.</span><input name="color" id="color" type="radio" value="red">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'red',
            )
        );
                
        $this->assertSame(
            '<input name="color" id="color" type="radio" value="blue">',
            $form->input(
                name: 'color',
                type: 'radio',
                value: 'blue',
            )
        );
    }
}