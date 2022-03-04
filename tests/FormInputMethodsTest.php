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
use Tobento\Service\Form\InputInterface;
use Tobento\Service\Form\Input;

/**
 * FormInputMethodsTest
 */
class FormInputMethodsTest extends TestCase
{
    public function testGetInputMethod()
    {
        $form = new Form(
            input: new Input([
                'color' => 'blue',
                'options' => [
                    'size' => 'small',
                ],
            ]),
        );
        
        $this->assertSame(
            'blue',
            $form->getInput(
                name: 'color',
            )
        );
        
        $this->assertSame(
            'small',
            $form->getInput(
                name: 'options.size',
            )
        );
        
        $this->assertSame(
            ['size' => 'small'],
            $form->getInput(
                name: 'options',
                withInput: true,
            )
        );        
        
        $this->assertSame(
            null,
            $form->getInput(
                name: 'cars',
            )
        );        
    }
    
    public function testGetInputMethodReturnsDefaultIfNotExists()
    {
        $form = new Form(
            input: new Input([]),
        );
        
        $this->assertSame(
            'blue',
            $form->getInput(
                name: 'color',
                default: 'blue',
            )
        );
        
        $this->assertSame(
            'small',
            $form->getInput(
                name: 'options.size',
                default: 'small',
                withInput: true,
            )
        );        
    }
    
    public function testGetInputMethodWithoutInputReturnsDefaultIfSet()
    {
        $form = new Form(
            input: new Input([
                'color' => 'blue',
                'options' => [
                    'size' => 'small',
                ],
            ]),
        );
        
        $this->assertSame(
            'yellow',
            $form->getInput(
                name: 'color',
                default: 'yellow',
                withInput: false,
            )
        );
        
        $this->assertSame(
            null,
            $form->getInput(
                name: 'color',
                withInput: false,
            )
        );        
        
        $this->assertSame(
            'large',
            $form->getInput(
                name: 'options.size',
                default: 'large',
                withInput: false,
            )
        );       
    }
    
    public function testHasInputMethod()
    {
        $form = new Form(
            input: new Input([
                'color' => 'blue',
                'options' => [
                    'size' => 'small',
                ],
            ]),
        );
        
        $this->assertTrue(
            $form->hasInput(
                name: 'color',
            )
        );
        
        $this->assertTrue(
            $form->hasInput(
                name: 'options.size',
            )
        );
        
        $this->assertTrue(
            $form->hasInput(
                name: 'options',
            )
        );
        
        $this->assertFalse(
            $form->hasInput(
                name: 'options.cars',
            )
        );
        
        $this->assertFalse(
            $form->hasInput(
                name: 'cars',
            )
        );        
    }
    
    public function testWithInputMethodReturnsNewFormInstance()
    {
        $form = new Form(
            input: new Input([]),
        );
        
        $newForm = $form->withInput(new Input([]));
        
        $this->assertFalse($form === $newForm);  
    }    
}