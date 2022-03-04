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

use Tobento\Service\Collection\Collection;

/**
 * Input
 */
class Input implements InputInterface
{
    /**
     * @var Collection
     */
    protected Collection $input;
    
    /**
     * Create a new Input.
     *
     * @param array $input The input data.
     */
    public function __construct(array $input)
    {
        $this->input = new Collection($input);
    }

    /**
     * Has input data for the specified name.
     *
     * @param string $name The name.
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->input->has($name);
    }
    
    /**
     * Returns the input data for the specified name.
     *
     * @param string $name The name.
     * @param mixed $default A default value
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->input->get($name, $default);
    }
}