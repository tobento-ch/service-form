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
 * ActiveElement
 */
class ActiveElement
{
    /**
     * Create a new ActiveElement.
     * 
     * @param string $name
     * @param null|string $id
     * @param null|string $value
     * @param null|string $label
     * @param null|string $group
     * @param null|string $type
     */
    public function __construct(
        protected string $name,
        protected null|string $id = null,
        protected null|string $value = null,
        protected null|string $label = null,
        protected null|string $group = null,
        protected null|string $type = null,
    ) {}
    
    /**
     * Returns the name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns the id.
     *
     * @return null|string
     */
    public function id(): null|string
    {
        return $this->id;
    }

    /**
     * Sets the value.
     *
     * @param string $value
     * @return static $this
     */
    public function setValue(string $value): static
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Returns the value.
     *
     * @return null|string
     */
    public function value(): null|string
    {
        return $this->value;
    }
    
    /**
     * Sets the label.
     *
     * @param string $label
     * @return static $this
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;
        return $this;
    }
        
    /**
     * Returns the label.
     *
     * @return null|string
     */
    public function label(): null|string
    {
        return $this->label;
    }
        
    /**
     * Returns the group.
     *
     * @return null|string
     */
    public function group(): null|string
    {
        return $this->group;
    }
    
    /**
     * Returns the type.
     *
     * @return null|string
     */
    public function type(): null|string
    {
        return $this->type;
    }
    
    /**
     * __get For array_column object support
     */
    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    /**
     * __isset For array_column object support
     */
    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }
}