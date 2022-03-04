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
 * FormTextareaMethodTest
 */
class FormTextareaMethodTest extends TestCase
{
    public function testTextarea()
    {
        $form = new Form();
        
        $this->assertSame(
            '<textarea name="description" id="description">Lorem ipsum</textarea>',
            $form->textarea(
                name: 'description',
                value: 'Lorem ipsum',
                attributes: [],
                withInput: true,
            )
        );
    }
    
    public function testTextareaEscapes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<textarea name="description" id="description">&lt;p&gt;Lorem ipsum&lt;/p&gt;</textarea>',
            $form->textarea(
                name: 'description',
                value: '<p>Lorem ipsum</p>',
            )
        );
    }    
    
    public function testEmptyIdAttributeRemovesIdAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<textarea name="description"></textarea>',
            $form->textarea(
                name: 'description',
                attributes: ['id' => ''],
            )
        );
    }

    public function testValueAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<textarea name="name" id="name">foo</textarea>',
            $form->textarea(
                name: 'name',
                value: 'foo',
            )
        );
    }
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<textarea class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly name="name" id="name"></textarea>',
            $form->textarea(
                name: 'name',
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
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
            '<textarea name="color" id="color">red</textarea>',
            $form->textarea(
                name: 'color',
            )
        );
        
        $this->assertSame(
            '<textarea name="color" id="color"></textarea>',
            $form->textarea(
                name: 'color',
                withInput: false,
            )
        );
        
        $this->assertSame(
            '<textarea name="options[size]" id="options_size">large</textarea>',
            $form->textarea(
                name: 'options[size]',
                withInput: true,
            )
        );
        
        $this->assertSame(
            '<textarea name="options[size]" id="options_size">large</textarea>',
            $form->textarea(
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
            '<span class="form-message error">Error color message.</span><textarea name="color" id="color"></textarea>',
            $form->textarea(
                name: 'color',
            )
        );
                
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><textarea name="options[size]" id="options_size"></textarea>',
            $form->textarea(
                name: 'options[size]',
            )
        );
        
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><textarea name="options[size]" id="options_size"></textarea>',
            $form->textarea(
                name: 'options.size',
            )
        );
    }    
}