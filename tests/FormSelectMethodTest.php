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
 * FormSelectMethodTest
 */
class FormSelectMethodTest extends TestCase
{
    public function testSelect()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
    }
    
    public function testSelectMultiple()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select multiple name="colors[]" id="colors"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'colors[]',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                selectAttributes: ['multiple'],
            )
        );
    }
    
    public function testEmptyIdAttributeRemovesIdAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors[]"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'colors[]',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                selectAttributes: ['id' => ''],
            )
        );
    }    

    public function testSelectedAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><option value="red" selected>Red</option><option value="blue" selected>Blue</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                selected: ['blue', 'red'],
            )
        );
    }
    
    public function testSelectedAttributeMultiple()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select multiple name="colors[]" id="colors"><option value="red" selected>Red</option><option value="blue" selected>Blue</option></select>',
            $form->select(
                name: 'colors[]',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                selected: ['blue', 'red'],
                selectAttributes: ['multiple'],
            )
        );
    } 
    
    public function testSelectAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly name="colors" id="colors"><option value="red">Red</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red'],
                selectAttributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testOptionAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><option class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                optionAttributes: ['red' => ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly']],
            )
        );
    }
    
    public function testOptionAttributesAttributeWithAsterix()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><option class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly readonly value="red">Red</option><option class="bar" data-bar=\'{&quot;bar&quot;:&quot;foo&quot;}\' readonly value="blue">Blue</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                optionAttributes: [
                    '*' => ['class' => 'bar', 'data-bar' => ['bar' => 'foo'], 'readonly'],
                    'red' => ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
                ],
            )
        );
    }
    
    public function testOptgroupAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><optgroup class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly label="group"><option value="red">Red</option></optgroup></select>',
            $form->select(
                name: 'colors',
                items: [
                    'group' => [
                        'red' => 'Red',
                    ],
                ],
                optgroupAttributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testEmptyOptionAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><option value="none">-----</option><option value="red">Red</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red'],
                emptyOption: ['none', '-----'],
            )
        );
    }
    
    public function testEmptyOptionAttributeEmptyArrayUsesDefaultValues()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><option value="_empty">---</option><option value="red">Red</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red'],
                emptyOption: [],
            )
        );
    }
    
    public function testWithEach()
    {
        $form = new Form();
        
        $items = ['red' => 'Red', 'blue' => 'Blue'];
        
        $this->assertSame(
            '<select name="colors" id="colors"><option value="red">RED</option><option value="blue">BLUE</option></select>',
            $form->select(
                name: 'colors',
                items: $form->each(items: $items, callback: function($item, $key): array {
                    // value:string, label:string|null
                    return [$key, strtoupper($item)];
                }),
            )
        );
    }
    
    public function testOptgroup()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><optgroup label="colors"><option value="red">Red</option><option value="blue">Blue</option></optgroup><optgroup label="sizes"><option value="small">Small</option></optgroup></select>',
            $form->select(
                name: 'colors',
                items: [
                    'colors' => [
                        'red' => 'Red',
                        'blue' => 'Blue',
                    ],
                    'sizes' => [
                        'small' => 'Small',
                    ],                    
                ],
            )
        );
    }
    
    public function testOptgroupWithOptionAttributes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<select name="colors" id="colors"><optgroup label="colors"><option class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly readonly value="red">Red</option><option class="bar" data-bar=\'{&quot;bar&quot;:&quot;foo&quot;}\' readonly value="blue">Blue</option></optgroup><optgroup label="sizes"><option class="bar" data-bar=\'{&quot;bar&quot;:&quot;foo&quot;}\' readonly value="small">Small</option></optgroup></select>',
            $form->select(
                name: 'colors',
                items: [
                    'colors' => [
                        'red' => 'Red',
                        'blue' => 'Blue',
                    ],
                    'sizes' => [
                        'small' => 'Small',
                    ],                    
                ],
                optionAttributes: [
                    '*' => ['class' => 'bar', 'data-bar' => ['bar' => 'foo'], 'readonly'],
                    'red' => ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
                ],
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
            '<select name="color" id="color"><option value="red" selected>Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'color',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
        
        $this->assertSame(
            '<select name="color" id="color"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'color',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                withInput: false,
            )
        );        

        $this->assertSame(
            '<select name="options[size]" id="options_size"><option value="small">Small</option><option value="large" selected>Large</option></select>',
            $form->select(
                name: 'options[size]',
                items: ['small' => 'Small', 'large' => 'Large'],
                withInput: true,
            )
        ); 
        
        $this->assertSame(
            '<select name="options[size]" id="options_size"><option value="small">Small</option><option value="large" selected>Large</option></select>',
            $form->select(
                name: 'options.size',
                items: ['small' => 'Small', 'large' => 'Large'],
            )
        );        
    }
    
    public function testInputDataMultiple()
    {
        $form = new Form(
            input: new Input([
                'colors' => ['red', 'blue'],
                'options' => [
                    'sizes' => ['small', 'large'],
                ],
            ]),
        );
        
        $this->assertSame(
            '<select name="colors" id="colors"><option value="red" selected>Red</option><option value="blue" selected>Blue</option></select>',
            $form->select(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );       

        $this->assertSame(
            '<select name="options[sizes]" id="options_sizes"><option value="small" selected>Small</option><option value="large" selected>Large</option></select>',
            $form->select(
                name: 'options[sizes]',
                items: ['small' => 'Small', 'large' => 'Large'],
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
            message: 'Error colors message.',
            key: 'colors',
        );
        
        $form->messages()->add(
            level: 'error',
            message: 'Error size message.',
            key: 'options.size',
        );   
        
        $this->assertSame(
            '<span class="form-message error">Error color message.</span><select name="color" id="color"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'color',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
        
        $this->assertSame(
            '<span class="form-message error">Error colors message.</span><select name="colors[]" id="colors"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'colors[]',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );        
                
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><select name="options[size]" id="options_size"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'options[size]',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
        
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><select name="options[size]" id="options_size"><option value="red">Red</option><option value="blue">Blue</option></select>',
            $form->select(
                name: 'options.size',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
    }    
}