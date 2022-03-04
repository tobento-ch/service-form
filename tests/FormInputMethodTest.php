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
 * FormInputMethodTest
 */
class FormInputMethodTest extends TestCase
{
    public function testInput()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="name" id="name" type="text">',
            $form->input(
                name: 'name',
            )
        );
    }
    
    public function testEmptyIdAttributeRemovesIdAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="name" type="text">',
            $form->input(
                name: 'name',
                attributes: ['id' => ''],
            )
        );
    }
    
    public function testTypeAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="name" id="name" type="tel">',
            $form->input(
                name: 'name',
                type: 'tel',
            )
        );
    }
    
    public function testHiddenTypeAttributeAddsNoId()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="name" type="hidden">',
            $form->input(
                name: 'name',
                type: 'hidden',
            )
        );
    }    
    
    public function testValueAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="name" id="name" type="text" value="foo">',
            $form->input(
                name: 'name',
                value: 'foo',
            )
        );
    }
    
    public function testValueAttributeEscapes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input name="name" id="name" type="text" value="&lt;p&gt;foo&lt;/p&gt;">',
            $form->input(
                name: 'name',
                value: '<p>foo</p>',
            )
        );
    }    
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' name="name" id="name" type="text">',
            $form->input(
                name: 'name',
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar']],
            )
        );
    }
    
    public function testBoolAttributes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<input class="foo" readonly name="name" id="name" type="text">',
            $form->input(
                name: 'name',
                attributes: ['class' => 'foo', 'readonly'],
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
            '<input name="color" id="color" type="text" value="red">',
            $form->input(
                name: 'color',
            )
        );
        
        $this->assertSame(
            '<input name="color" id="color" type="text">',
            $form->input(
                name: 'color',
                withInput: false,
            )
        );
        
        $this->assertSame(
            '<input name="options[size]" id="options_size" type="text" value="large">',
            $form->input(
                name: 'options[size]',
                withInput: true,
            )
        );
        
        $this->assertSame(
            '<input name="options[size]" id="options_size" type="text" value="large">',
            $form->input(
                name: 'options.size',
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
            '<span class="form-message error">Error color message.</span><input name="color" id="color" type="text">',
            $form->input(
                name: 'color',
            )
        );
                
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><input name="options[size]" id="options_size" type="text">',
            $form->input(
                name: 'options[size]',
            )
        );
        
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><input name="options[size]" id="options_size" type="text">',
            $form->input(
                name: 'options.size',
            )
        );
    }    
}