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

namespace Tobento\Service\Form;

/**
 * ActiveElementsInterface
 */
interface ActiveElementsInterface
{
    /**
     * Add an active element.
     *
     * @param string $name
     * @param null|string $id
     * @param null|string $value
     * @param null|string $label
     * @param null|string $group
     * @param null|string $type
     * @return static $this
     */
    public function add(
        string $name,
        null|string $id = null,
        null|string $value = null,
        null|string $label = null,
        null|string $group = null,
        null|string $type = null,
    ): static;
    
    /**
     * Returns active element by name.
     *
     * @param string $name
     * @return null|ActiveElement
     */
    public function get(string $name): null|ActiveElement; 
    
    /**
     * Returns a new instance with the filtered elements.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static;
        
    /**
     * Returns all active elements.
     *
     * @return array<string, ActiveElement>
     */    
    public function all(): array;
}