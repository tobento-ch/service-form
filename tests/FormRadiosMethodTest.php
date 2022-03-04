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
 * FormRadiosMethodTest
 */
class FormRadiosMethodTest extends TestCase
{
    public function testCheckboxes()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="colors_1" name="colors" type="radio" value="red"><label for="colors_1">Red</label></span><span class="form-wrap-radio"><input id="colors_2" name="colors" type="radio" value="blue" checked><label for="colors_2">Blue</label></span>',
            $form->radios(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                selected: 'blue',
                attributes: [],
                labelAttributes: [],
                withInput: true,
                wrapClass: 'form-wrap-radio'
            )
        );
    }
    
    public function testNameAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="options_size_1" name="options[size]" type="radio" value="red"><label for="options_size_1">Red</label></span>',
            $form->radios(
                name: 'options[size]',
                items: ['red' => 'Red'],
            )
        );
    }
    
    public function testNameAttributeWitNotation()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="options_size_1" name="options[size]" type="radio" value="red"><label for="options_size_1">Red</label></span>',
            $form->radios(
                name: 'options.size',
                items: ['red' => 'Red'],
            )
        );
    }    
    
    public function testSelectedAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="colors_1" name="colors" type="radio" value="red"><label for="colors_1">Red</label></span><span class="form-wrap-radio"><input id="colors_2" name="colors" type="radio" value="blue" checked><label for="colors_2">Blue</label></span>',
            $form->radios(
                name: 'colors',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                selected: 'blue',
            )
        );
    }
    
    public function testAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly id="colors_1" name="colors" type="radio" value="red"><label for="colors_1">Red</label></span>',
            $form->radios(
                name: 'colors',
                items: ['red' => 'Red'],
                attributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testLabelAttributesAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="colors_1" name="colors" type="radio" value="red"><label class="foo" data-bar=\'{&quot;foo&quot;:&quot;bar&quot;}\' readonly for="colors_1">Red</label></span>',
            $form->radios(
                name: 'colors',
                items: ['red' => 'Red'],
                labelAttributes: ['class' => 'foo', 'data-bar' => ['foo' => 'bar'], 'readonly'],
            )
        );
    }
    
    public function testWrapClassAttribute()
    {
        $form = new Form();
        
        $this->assertSame(
            '<span class="foo"><input id="colors_1" name="colors" type="radio" value="red"><label for="colors_1">Red</label></span>',
            $form->radios(
                name: 'colors',
                items: ['red' => 'Red'],
                wrapClass: 'foo',
            )
        );
    }    
    
    public function testWithEach()
    {
        $form = new Form();
        
        $items = ['red' => 'Red', 'blue' => 'Blue'];
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="colors_red" name="colors" type="radio" value="red"><label for="colors_red">RED</label></span><span class="form-wrap-radio"><input id="colors_blue" name="colors" type="radio" value="blue"><label for="colors_blue">BLUE</label></span>',
            $form->radios(
                name: 'colors',
                items: $form->each(items: $items, callback: function($item, $key): array {
                    // value:string, label:string|null, array-key:string|null
                    return [$key, strtoupper($item), $key];
                }),
            )
        );
    }
    
    public function testWithEachWithoutArrayKeyGeneratesNumberedKeys()
    {
        $form = new Form();
        
        $items = ['red' => 'Red', 'blue' => 'Blue'];
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="colors_1" name="colors" type="radio" value="red"><label for="colors_1">RED</label></span><span class="form-wrap-radio"><input id="colors_2" name="colors" type="radio" value="blue"><label for="colors_2">BLUE</label></span>',
            $form->radios(
                name: 'colors',
                items: $form->each(items: $items, callback: function($item, $key): array {
                    // value:string, label:string|null, array-key:string|null
                    return [$key, strtoupper($item)];
                }),
            )
        );
    }
    
    public function testWithEachWithoutLabel()
    {
        $form = new Form();
        
        $items = ['red' => 'Red', 'blue' => 'Blue'];
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="colors_1" name="colors" type="radio" value="red"></span><span class="form-wrap-radio"><input id="colors_2" name="colors" type="radio" value="blue"></span>',
            $form->radios(
                name: 'colors',
                items: $form->each(items: $items, callback: function($item, $key): array {
                    // value:string, label:string|null, array-key:string|null
                    return [$key];
                }),
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
            '<span class="form-wrap-radio"><input id="color_1" name="color" type="radio" value="red" checked><label for="color_1">Red</label></span><span class="form-wrap-radio"><input id="color_2" name="color" type="radio" value="blue"><label for="color_2">Blue</label></span>',
            $form->radios(
                name: 'color',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
        
        $this->assertSame(
            '<span class="form-wrap-radio"><input id="color_1" name="color" type="radio" value="red"><label for="color_1">Red</label></span><span class="form-wrap-radio"><input id="color_2" name="color" type="radio" value="blue"><label for="color_2">Blue</label></span>',
            $form->radios(
                name: 'color',
                items: ['red' => 'Red', 'blue' => 'Blue'],
                withInput: false,
            )
        );        

        $this->assertSame(
            '<span class="form-wrap-radio"><input id="options_size_1" name="options[size]" type="radio" value="small"><label for="options_size_1">Small</label></span><span class="form-wrap-radio"><input id="options_size_2" name="options[size]" type="radio" value="large" checked><label for="options_size_2">Large</label></span>',
            $form->radios(
                name: 'options[size]',
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
            message: 'Error size message.',
            key: 'options.size',
        );        
        
        $this->assertSame(
            '<span class="form-message error">Error color message.</span><span class="form-wrap-radio"><input id="color_1" name="color" type="radio" value="red"><label for="color_1">Red</label></span><span class="form-wrap-radio"><input id="color_2" name="color" type="radio" value="blue"><label for="color_2">Blue</label></span>',
            $form->radios(
                name: 'color',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
                
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><span class="form-wrap-radio"><input id="options_size_1" name="options[size]" type="radio" value="red"><label for="options_size_1">Red</label></span><span class="form-wrap-radio"><input id="options_size_2" name="options[size]" type="radio" value="blue"><label for="options_size_2">Blue</label></span>',
            $form->radios(
                name: 'options[size]',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
        
        $this->assertSame(
            '<span class="form-message error">Error size message.</span><span class="form-wrap-radio"><input id="options_size_1" name="options[size]" type="radio" value="red"><label for="options_size_1">Red</label></span><span class="form-wrap-radio"><input id="options_size_2" name="options[size]" type="radio" value="blue"><label for="options_size_2">Blue</label></span>',
            $form->radios(
                name: 'options.size',
                items: ['red' => 'Red', 'blue' => 'Blue'],
            )
        );
    }    
}