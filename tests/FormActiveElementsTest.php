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
use Tobento\Service\Form\ActiveElements;
use Tobento\Service\Form\ActiveElementsInterface;

/**
 * FormActiveElementsTest
 */
class FormActiveElementsTest extends TestCase
{
    public function testReturnsActiveElementsInterface()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $this->assertInstanceof(
            ActiveElementsInterface::class,
            $form->getActiveElements()
        );
    }
    
    public function testEmptyElementsInNoneIsActive()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $this->assertSame(
            [],
            $form->getActiveElements()->all()
        );
    }
    
    public function testInputMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->input(name: 'color', value: 'blue');
        
        $el = $form->getActiveElements()->get('color');
        
        $this->assertSame('color', $el->name());
        $this->assertSame('color', $el->id());
        $this->assertSame('blue', $el->value());
        $this->assertSame('Color', $el->label());
        $this->assertSame('input.text', $el->type());
        $this->assertSame('color', $el->group());
    }
    
    public function testCheckboxesMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->checkboxes(
            name: 'colors',
            items: ['red' => 'Red', 'blue' => 'Blue'],
            selected: ['blue'],
        );
        
        $el = $form->getActiveElements()->get('colors');
        
        $this->assertSame('colors', $el->name());
        $this->assertSame('colors', $el->id());
        $this->assertSame('Blue', $el->value());
        $this->assertSame('Colors', $el->label());
        $this->assertSame('input.checkbox', $el->type());
        $this->assertSame('colors', $el->group());
        
        $this->assertSame(
            1,
            count($form->getActiveElements()->all())
        );
    }
    
    public function testRadiosMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->radios(
            name: 'colors',
            items: ['red' => 'Red', 'blue' => 'Blue'],
            selected: 'blue',
        );
        
        $el = $form->getActiveElements()->get('colors');
        
        $this->assertSame('colors', $el->name());
        $this->assertSame('colors', $el->id());
        $this->assertSame('Blue', $el->value());
        $this->assertSame('Colors', $el->label());
        $this->assertSame('input.radio', $el->type());
        $this->assertSame('colors', $el->group());
        
        $this->assertSame(
            1,
            count($form->getActiveElements()->all())
        );
    }
    
    public function testLabelMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->label(text: 'Color', for: 'color');
        
        $this->assertSame(
            [],
            $form->getActiveElements()->all()
        );
    }
    
    public function testSelectMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->select(
            name: 'colors',
            items: ['red' => 'Red', 'blue' => 'Blue'],
            selected: ['blue'],
        );
        
        $el = $form->getActiveElements()->get('colors');
        
        $this->assertSame('colors', $el->name());
        $this->assertSame('colors', $el->id());
        $this->assertSame('Blue', $el->value());
        $this->assertSame('Colors', $el->label());
        $this->assertSame('option', $el->type());
        $this->assertSame('colors', $el->group());
    }
    
    public function testTextareaMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->textarea(
            name: 'description',
            value: 'Lorem',
        );
        
        $el = $form->getActiveElements()->get('description');
        
        $this->assertSame('description', $el->name());
        $this->assertSame('description', $el->id());
        $this->assertSame('Lorem', $el->value());
        $this->assertSame('Description', $el->label());
        $this->assertSame('textarea', $el->type());
        $this->assertSame('description', $el->group());
    }
    
    public function testOptionMethod()
    {
        $form = new Form(activeElements: new ActiveElements());
        
        $form->option(
            value: 'red',
            text: 'Red',
            attributes: [],
            selected: ['red'],
            name: 'colors'
        );
        
        $el = $form->getActiveElements()->get('colors');
        
        $this->assertSame('colors', $el->name());
        $this->assertSame('colors', $el->id());
        $this->assertSame('Red', $el->value());
        $this->assertSame('Colors', $el->label());
        $this->assertSame('option', $el->type());
        $this->assertSame('colors', $el->group());
    }
}