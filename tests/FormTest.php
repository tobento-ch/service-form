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

/**
 * FormTest
 */
class FormTest extends TestCase
{
    public function testNameToArrayMethod()
    {
        $form = new Form();
        
        $this->assertSame(
            'user[firstname]',
            $form->nameToArray('user.firstname')
        );
        
        $this->assertSame(
            'user[option][color]',
            $form->nameToArray('user.option.color')
        );
        
        $this->assertSame(
            'user[]',
            $form->nameToArray('user.')
        );        
    }
    
    public function testNameToNotationMethod()
    {
        $form = new Form();
        
        $this->assertSame(
            'user.firstname',
            $form->nameToNotation('user[firstname]')
        );
        
        $this->assertSame(
            'user.option.color',
            $form->nameToNotation('user[option][color]')
        );
        
        $this->assertSame(
            'user.option',
            $form->nameToNotation('user[option][]')
        );        
        
        $this->assertSame(
            'user',
            $form->nameToNotation('user[]')
        );        
    }
    
    public function testNameToIdMethod()
    {
        $form = new Form();
        
        $this->assertSame(
            'user_firstname',
            $form->nameToId('user[firstname]')
        );
        
        $this->assertSame(
            'user_option_color',
            $form->nameToId('user[option][color]')
        );
        
        $this->assertSame(
            'user_option',
            $form->nameToId('user[option][]')
        );        
        
        $this->assertSame(
            'user',
            $form->nameToId('user[]')
        );        
    }
    
    public function testHasArrayNotationMethod()
    {
        $form = new Form();
        
        $this->assertTrue(
            $form->hasArrayNotation('user.firstname')
        );
        
        $this->assertTrue(
            $form->hasArrayNotation('user.option.color')
        );
        
        $this->assertTrue(
            $form->hasArrayNotation('user.')
        );
        
        $this->assertFalse(
            $form->hasArrayNotation('user')
        );
        
        $this->assertFalse(
            $form->hasArrayNotation('')
        );        
    }    
}