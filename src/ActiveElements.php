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
 * ActiveElements
 */
class ActiveElements implements ActiveElementsInterface
{
    /**
     * @var array<string, ActiveElement>
     */
    protected array $activeElements = [];
    
    /**
     * @var array
     */
    protected array $groups = [];
    
    /**
     * @var array
     */
    protected array $labels = [];
    
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
    ): static {        
        // ignore hidden elements
        if ($type === 'input.hidden') {
            return $this;
        }       
        
        if ($type === 'label') {
            $id = $id ?? $name;
            $this->labels[$id] = $label;   
        } else {
            $group = $group ?? $name;
            $this->groups[$group][] = new ActiveElement($name, $id, $value, $label, $group, $type);
        }
        
        return $this;
    }
    
    /**
     * Returns active element by name.
     *
     * @param string $name
     * @return null|ActiveElement
     */
    public function get(string $name): null|ActiveElement
    {
        $this->createElements();
        
        return $this->activeElements[$name] ?? null;
    }
    
    /**
     * Returns a new instance with the filtered elements.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        $new = clone $this;
        $new->activeElements = array_filter($this->all(), $callback);
        return $new;
    }
        
    /**
     * Returns all active elements.
     *
     * @return array<string, ActiveElement>
     */
    public function all(): array
    {
        $this->createElements();
        
        return $this->activeElements;
    }
    
    /**
     * Create the elements.
     *
     * @return void
     */
    protected function createElements(): void
    {
        foreach($this->groups as $key => $elements)
        {
            // assign label
            foreach($elements as $el)
            {
                if (
                    is_null($el->label())
                    && isset($this->labels[$el->id()])
                ) {
                    $el->setLabel($this->labels[$el->id()]);
                }
            }
            
            $firstEl = $elements[array_key_first($elements)];
            $value = implode(', ', array_column($elements, 'value'));
            $label = implode(', ', array_column($elements, 'label'));            
            $id = $this->nameToId($key);
            
            // handle radio, checkbox, option types:
            if (in_array($firstEl->type(), ['input.radio', 'input.checkbox', 'option'])) {
                $value = $label;
                $label = $this->labels[$id] ?? $this->nameToLabel($key);
            }
            
            $name = $this->nameToNotation($key);
            
            $this->activeElements[$name] = new ActiveElement(
                name: $name,
                id: $id,
                value: $value,
                label: empty($label) ? $this->nameToLabel($name) : $label,
                group: $key,
                type: $firstEl->type(),
            );
            
            unset($this->groups[$key]);
        }
    }
    
    /**
     * Generates a name as array to a notation based name.
     *
     * @param string $name The name.
     * @return string The generated name.
     */
    protected function nameToNotation(string $name): string
    {
        return str_replace(['[]', '[', ']'], ['', '.', ''], $name);
    }

    /**
     * Generates a name to a valid id.
     *
     * @param string $name The name.
     * @return string The generated name id.
     */
    protected function nameToId(string $name): string
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '_', ''], $name);
    }
    
    /**
     * Generates a name to a label name.
     *
     * @param string $name The name.
     * @return string The generated label.
     */
    protected function nameToLabel(string $name): string
    {
        $label = str_replace(['.', '[]', '[', ']'], [' ', ' ', ' ', ' '], $name);
        return ucwords($label);
    }
}