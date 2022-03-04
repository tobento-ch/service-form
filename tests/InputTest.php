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
use Tobento\Service\Form\InputInterface;
use Tobento\Service\Form\Input;

/**
 * InputTest
 */
class InputTest extends TestCase
{
    public function testThatImplementsInputInterface()
    {
        $this->assertInstanceOf(
            InputInterface::class,
            new Input([])
        );
    }
    
    public function testHasMethod()
    {
        $input = new Input([
            'color' => 'blue',
            'options' => [
                'size' => 'small',
            ],
        ]);
        
        $this->assertTrue($input->has('color'));
        $this->assertTrue($input->has('options'));
        $this->assertTrue($input->has('options.size'));
        
        $this->assertFalse($input->has('cars'));
        $this->assertFalse($input->has('options.color'));
    }
    
    public function testGetMethod()
    {
        $input = new Input([
            'color' => 'blue',
            'options' => [
                'size' => 'small',
            ],
        ]);
        
        $this->assertSame('blue', $input->get('color'));
        $this->assertSame(['size' => 'small'], $input->get('options'));
        $this->assertSame('small', $input->get('options.size'));
    }
    
    public function testGetMethodReturnsDefaultValueIfNotExists()
    {
        $input = new Input([]);

        $this->assertSame(null, $input->get('color'));
        $this->assertSame(null, $input->get('options.size'));
        
        $this->assertSame('blue', $input->get('color', 'blue'));
        $this->assertSame('small', $input->get('options.size', 'small'));
    }    
}