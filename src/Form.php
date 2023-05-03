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

use Tobento\Service\Message\HasMessages;
use Tobento\Service\Message\MessagesInterface;
use Stringable;
use Closure;

/**
 * Form
 */
class Form
{
    use HasMessages;

    /**
     * The key used for method spoofing.
     */
    public const METHOD_SPOOFING_KEY = '_method';
    
    /**
     * The form methods that should be spoofed, in uppercase.
     *
     * @var array
     */
    protected $spoofedMethods = ['DELETE', 'PATCH', 'PUT'];

    /**
     * @var array<int, string> The rendered message keys.
     */
    protected array $renderedMessageKeys = [];
    
    /**
     * @var string The notation to identify that it is an array.
     */
    protected string $arrayNotation = '.';
            
    /**
     * Create a new Form instance.
     *
     * @param null|InputInterface $input
     * @param null|TokenizerInterface $tokenizer
     * @param null|ActiveElementsInterface $activeElements
     * @param null|MessagesInterface $messages
     */
    public function __construct(
        protected null|InputInterface $input = null,
        protected null|TokenizerInterface $tokenizer = null,
        protected null|ActiveElementsInterface $activeElements = null,
        null|MessagesInterface $messages = null,
    ) {
        $this->messages = $messages;
    }

    /**
     * Returns a new instance with the specified input.
     *
     * @param null|InputInterface $input
     * @return static
     */
    public function withInput(null|InputInterface $input): static
    {
        $new = clone $this;
        $new->input = $input;
        return $new;
    }
    
    /**
     * Returns a new instance with the specified messages.
     *
     * @param null|MessagesInterface $messages
     * @return static
     */
    public function withMessages(null|MessagesInterface $messages): static
    {
        $new = clone $this;
        $new->messages = $messages;
        return $new;
    } 

    /**
     * Generates the form element. 
     *
     * @param array $attributes Any additional attributes for the form tag. 
     *                          For instance, ['enctype' => 'multipart/form-data']
     * @return string
     */
    public function form(
        array $attributes = []
    ): string {
        
        $html = '';
        
        $attributes['method'] ??= 'POST';
        $attributes['method'] = strtoupper($attributes['method']);
        
        if (in_array($attributes['method'], $this->spoofedMethods)) {
            // method spoofing
            $html .= $this->input(self::METHOD_SPOOFING_KEY, 'hidden', $attributes['method']);
                
            $attributes['method'] = 'POST';
        }
        
        // On any other method than GET, the form needs a token.
        if ($attributes['method'] !== 'GET') {
            $html .= $this->generateTokenInput();
        }

        return '<form'.$this->formatAttributes($attributes).'>'.$html;
    }
    
    /**
     * Closes the form.
     * 
     * @return string
     */
    public function close(): string
    {
        return '</form>';
    }
    
    /**
     * Generates a fieldset with the legend element. Important is to close it after with fieldsetClose().
     * 
     * @param string $legend The legend text.
     * @param array $attributes Any attributes for the fieldset tag. For instance, ['class' => 'class-name']
     * @param array $legendAttributes Any attributes for the legend tag. For instance, ['class' => 'class-name']
     * @return string
     */
    public function fieldset(string $legend, array $attributes = [], array $legendAttributes = []): string
    {
        return '<fieldset'.$this->formatAttributes($attributes).'><legend'
            .$this->formatAttributes($legendAttributes).'>'
            .$this->esc($legend).'</legend>';
    }

    /**
     * Closes the fieldset.
     * 
     * @return string The closing fieldset.
     */
    public function fieldsetClose(): string
    {
        return '</fieldset>';
    }
    
    /**
     * Generates a label element.
     * 
     * @param string $text The label text.
     * @param null|string $for The for attribute should be equal to the id attribute
     *     of the related element to bind them together.
     * @param array $attributes Any attributes. For instance, ['class' => 'class-name'],
     * @param string $requiredText
     * @param string $optionalText
     * @return string The label element based on the attributes set.
     */
    public function label(
        string $text,
        null|string $for = null,
        array $attributes = [],
        string $requiredText = '',
        string $optionalText = '',
    ): string {
        
        if (!is_null($for)) {
            $attributes['for'] = $this->nameToId($for);
            
            $this->activeElements?->add(
                name: $for,
                id: $attributes['for'],
                label: $text,
                type: 'label',
            );            
        }
        
        $html = $this->esc($text);
        
        if (!empty($requiredText)) {
            $html .= '<span class="required">'.$this->esc($requiredText).'</span>';
        }
        
        if (!empty($optionalText)) {
            $html .= '<span class="optional">'.$this->esc($optionalText).'</span>';
        }
        
        return '<label'.$this->formatAttributes($attributes).'>'.$html.'</label>';
    }
    
    /**
     * Generates an input element. 
     *
     * @param string $name The name attribute of the element.
     * @param string $type The type attribute of the element such as text, date etc.
     * @param null|string $value The value.
     * @param array $attributes Any attributes. For instance, ['class' => 'class-name']
     * @param mixed $selected The selected value(s) for checkbox or radio type.
     * @param bool $withInput If the value should be repopulated with the input data.
     * @return string The generated element.
     */
    public function input(
        string $name,
        string $type = 'text',
        null|string $value = null,
        array $attributes = [],
        mixed $selected = null,
        bool $withInput = true,
    ): string {
        $attributes['name'] = $this->nameToArray($name);
        
        if (!isset($attributes['id']) && $type !== 'hidden') {
            $attributes['id'] = $this->nameToId($name);
        }
        
        if (empty($attributes['id'])) {
            unset($attributes['id']);
        }
        
        $attributes['type'] = $type;
        
        // Special treatment for radio and checkbox types.
        if ($type === 'radio' || $type === 'checkbox') {
        
            if ($value !== null) {
                $attributes['value'] = $value;
            }
            
            // get input without default value as collection
            // makes type check
            if ($this->hasInput($name) && $withInput) {
                $input = $this->getInput($name);   
            } else {
                $input = $selected;
            }
            
            if (!is_array($input)) {
                $input = [$input];
            }

            if (in_array($value, $input, true)) {
                $attributes[] = 'checked';
                
                $this->activeElements?->add(
                    name: $name,
                    id: $attributes['id'] ?? null,
                    value: (string)$value,
                    type: 'input.'.$type,
                    group: $this->nameToGroup($name, 'input.'.$type),
                );
            }
            
            // just render message with same name once.                
            $messageKey = $this->nameToNotation($name);

            if (in_array($messageKey, $this->renderedMessageKeys)) {
                return '<input'.$this->formatAttributes($attributes).'>';
            }
            
            return $this->getMessage($name).'<input'.$this->formatAttributes($attributes).'>';
        }
        
        $input = $this->getInput($name, $value, $withInput);
        
        if (!is_scalar($input)) {
            $input = null;
        }
        
        $value = ($input === null) ? null : $input;
        
        if (!empty($value)) {
            $this->activeElements?->add(
                name: $name,
                id: $attributes['id'] ?? null,
                value: (string)$value,
                type: 'input.'.$type,
            );
        }
        
        if ($value !== null) {
            $attributes['value'] = $value;
        }
        
        return $this->getMessage($name).'<input'.$this->formatAttributes($attributes).'>';
    }

    /**
     * Generates a textarea element. 
     *
     * @param string $name The name attribute of the element.
     * @param null|string $value The value.
     * @param array $attributes Any attributes. For instance, ['class' => 'class-name']
     * @param bool $withInput If the value should be repopulated with the input data.
     * @return string The generated element.
     */
    public function textarea(
        string $name,
        null|string $value = null,
        array $attributes = [],
        bool $withInput = true
    ): string {
        $attributes['name'] = $this->nameToArray($name);
        
        if (!isset($attributes['id'])) {
            $attributes['id'] = $this->nameToId($name);
        }
        
        if (empty($attributes['id'])) {
            unset($attributes['id']);
        }

        if (!empty($value)) {
            $this->activeElements?->add(
                name: $name,
                id: $attributes['id'] ?? null,
                value: $value,
                type: 'textarea'
            );
        } else {
            $value = '';
        }
        
        $value = $this->esc($this->getInput($name, $value, $withInput));
        
        return $this->getMessage($name).'<textarea'.$this->formatAttributes($attributes).'>'.$value.'</textarea>';
    }

    /**
     * Generates radio elements. 
     *
     * @param string $name The name attribute of the element.
     * @param iterable $items The radio elements as array, multidimensional array or array with objects.
     * @param null|string $selected The selected value.
     * @param array $attributes Any attributes for the radios. For instance, ['class' => 'class-name']
     * @param array $labelAttributes Any attributes for the radios label. For instance, ['class' => 'class-name']
     * @param bool $withInput If the value should be repopulated with the input data.
     * @param string $wrapClass The class for the wrap element.
     * @return string The generated element.
     */
    public function radios(
        string $name,
        iterable $items = [],
        null|string $selected = null,
        array $attributes = [],
        array $labelAttributes = [],
        bool $withInput = true,
        string $wrapClass = 'form-wrap-radio'
    ): string {

        $html = '';
        $index = 0;
        $message = $this->getMessage($name);
        
        foreach($items as $key => $item)
        {
            $attr = $attributes;
            
            $index++;
            
            [$value, $label, $key] = $this->ensureItem($items, $item, $key);
            
            $keyId = $key ?? $index;
            
            $attr['id'] = isset($attr['id'])
                ? $this->nameToId($attr['id'].'_'.$keyId)
                : $this->nameToId($name.'_'.$keyId);
            
            $html .= '<span class="'.$this->esc($wrapClass).'">';
            
            $html .= $this->input(
                name: $name,
                type: 'radio',
                value: $value,
                attributes: $attr,
                selected: $selected,
                withInput: $withInput,
            );
            
            if ($label) {
                $html .= $this->label($label, $attr['id'], $labelAttributes);
            }
            
            $html .= '</span>';
        }
        
        return $message.$html;
    }
    
    /**
     * Generates checkbox elements. 
     *
     * @param string $name The name attribute of the element.
     * @param iterable $items The items.
     * @param array $selected The selected checkbox values.
     * @param array $attributes Any attributes for the checkboxes. For instance, ['class' => 'class-name']
     * @param array $labelAttributes Any attributes for the checkboxes label. For instance, ['class' => 'class-name']
     * @param bool $withInput If the value should be repopulated with the input data.
     * @param string $wrapClass The class for the wrap element.
     * @return string The generated element.
     */
    public function checkboxes(
        string $name,
        iterable $items = [],
        array $selected = [],
        array $attributes = [],
        array $labelAttributes = [],
        bool $withInput = true,
        string $wrapClass = 'form-wrap-checkbox'
    ): string {

        $html = '';
        $index = 0;
        
        foreach($items as $key => $item)
        {
            $attr = $attributes;
            
            $index++;
            
            [$value, $label, $key] = $this->ensureItem($items, $item, $key);

            $keyId = $key ?? $index;
            
            $attr['id'] = isset($attr['id'])
                ? $this->nameToId($attr['id'].'_'.$keyId)
                : $this->nameToId($name.'_'.$keyId);
            
            $html .= '<span class="'.$this->esc($wrapClass).'">';
            
            $inputName = $this->nameToArray($name).'[]';
            
            if (!is_null($key)) {
                $inputName = $this->nameToArray($name).'['.$key.']';
            }
            
            $html .= $this->input(
                name: $inputName,
                type: 'checkbox',
                value: $value, 
                attributes: $attr,
                selected: $selected,
                withInput: $withInput,
            );
            
            if ($label) {
                $html .= $this->label($label, $attr['id'], $labelAttributes);
            }
            
            $html .= '</span>';
        }
            
        return $this->getMessage($name).$html;
    }
    
    /**
     * Generates a select element with its options. 
     *
     * @param string $name The name attribute of the element.
     * @param iterable $items The items. Multidimensional array for optgroups.
     * @param mixed $selected The selected value(s).
     * @param array $selectAttributes Any attributes for the select element. For instance, ['class' => 'class-name']
     * @param array $optionAttributes Any attributes for a single option. For instance, [45 => ['class' => 'class-name']]
     * @param array $optgroupAttributes Any attributes for the optgroups. For instance, ['class' => 'class-name']
     * @param null|array $emptyOption An empty option value. If not null it appends empty option.
     * @param bool $withInput If the value should be repopulated with the input data.
     * @return string The generated element.
     */
    public function select(
        string $name,
        iterable $items = [],
        mixed $selected = null,
        array $selectAttributes = [],
        array $optionAttributes = [],
        array $optgroupAttributes = [],
        null|array $emptyOption = null,
        bool $withInput = true
    ): string {

        $selectAttributes['name'] = $this->nameToArray($name);

        if (!isset($selectAttributes['id'])) {
            $selectAttributes['id'] = $this->nameToId($name);
        }
        
        if (empty($selectAttributes['id'])) {
            unset($selectAttributes['id']);
        }        
        
        $input = $this->getInput($name, $selected, $withInput);
        
        // for multiple attribute we need an array anyway.
        if (!is_array($input)) {
            $input = [$input];
        }
        
        $html = '<select'.$this->formatAttributes($selectAttributes).'>';
        
        if ($emptyOption !== null) {
            $emptyValue = $emptyOption[0] ?? '_empty';
            
            $html .= $this->option(
                $emptyValue,
                $emptyOption[1] ?? '---',
                array_merge(
                    $optionAttributes['*'] ?? [],
                    $optionAttributes[$emptyValue] ?? []
                ),
                $input,
                $name
            );
        }
                
        foreach($items as $key => $item)
        {
            if (is_iterable($item)) {
                $html .= $this->optgroup($items, $item, $key, $optgroupAttributes, $optionAttributes, $input, $name);
            } else {
                
                [$value, $label] = $this->ensureItem($items, $item, $key);
                
                $attributes = array_merge(
                    $optionAttributes['*'] ?? [],
                    $optionAttributes[$value] ?? []
                );
                
                $html .= $this->option($value, $label, $attributes, $input, $name);
            }
        }
        
        $html .= '</select>';
        
        $name = $this->nameToNotation($name);
        
        return $this->getMessage($name).$html;
    }
    
    /**
     * Generates option element. 
     *
     * @param string $value
     * @param null|string $text
     * @param array $attributes
     * @param mixed $selected
     * @param null|string $name
     * @return string The generated element.
     */
    public function option(
        string $value,
        null|string $text = null,
        array $attributes = [],
        mixed $selected = null,
        null|string $name = null
    ): string {
        $attributes['value'] = $value;
                
        if (!is_array($selected)) {
            $selected = [$selected];
        }
        
        if (in_array($value, $selected, true)) {
            $attributes[] = 'selected';
            
            if ($name) {
                $this->activeElements?->add(
                    name: $name,
                    id: $attributes['id'] ?? null,
                    value: $value,
                    label: $text,
                    type: 'option',
                    group: $this->nameToGroup($name, 'option'),
                );
            }
        }
        
        $html = '<option'.$this->formatAttributes($attributes).'>';
        
        if ($text) {
            $html .= $this->esc($text);
        }
        
        $html .= '</option>';
        
        return $html;
    }    
    
    /**
     * Generates a datalist.
     *
     * @param string $name The name for the button.
     * @param iterable $items The datalist options. ['firexfox', 'chrome']
     * @param array $attributes Any attributes. For instance, ['class' => 'class-name']
     * @return string
     */
    public function datalist(string $name, iterable $items = [], array $attributes = []): string
    {
        $attributes['id'] = $this->nameToId($name);
        
        $html = '';
        
        foreach($items as $key => $item)
        {
            [$value, $label] = $this->ensureItem($items, $item, $key);
            
            $label = $label ?: $value;
                
            $html .= '<option value="'.$this->esc($label).'"></option>';
        }
        
        return '<datalist'.$this->formatAttributes($attributes).'>'.$html.'</datalist>';
    }
    
    /**
     * Generates a button.
     * 
     * @param string $text The text for the button.
     * @param array $attributes Any attributes. For instance, ['class' => 'class-name']
     * @param bool $escText True escaping text, otherwise not.
     * @return string
     */
    public function button(string $text, array $attributes = [], bool $escText = true): string
    {
        $attributes['type'] ??= 'submit';
        
        if ($escText) {
            $text = $this->esc($text);
        }
        
        return '<button'.$this->formatAttributes($attributes).'>'.$text.'</button>';
    }

    /**
     * Returns the message for the specified key.
     *
     * @param string $key The key name of the element.
     * @return string
     */
    public function getMessage(string $key): string
    {
        if (str_ends_with($key, '[]')) {
            return '';
        }
        
        $key = $this->nameToNotation($key);
        
        // get first message if any.
        $message = $this->messages()->key($key)->first();
        
        if (is_null($message)) {
            return '';
        }
        
        $this->renderedMessageKeys[] = $key;
        
        return '<span class="form-message '.$this->esc($message->level()).'">'
            .$this->esc($message->message()).'</span>';
    }

    /**
     * Returns the rendered message keys.
     * 
     * @return array<int, string>
     */
    public function getRenderedMessageKeys(): array
    {
        return $this->renderedMessageKeys;
    }
    
    /**
     * Returns the active elements.
     * 
     * @return null|ActiveElementsInterface
     */
    public function getActiveElements(): null|ActiveElementsInterface
    {
        return $this->activeElements;
    }

    /**
     * If the element is active.
     * 
     * @param string $name The name attribute of the element.
     * @param string $value The element value.
     * @param mixed $default The default value to be active.
     * @return bool True if active, otherwise false.
     */
    public function isActive(string $name, string $value, mixed $default = null): bool
    {
        $inputValue = $this->getInput($name, $default);
        
        if (is_null($inputValue)) {
            return false;
        }
        
        return $inputValue === $value;
    }

    /**
     * Returns the tokenizer.
     *
     * @return null|TokenizerInterface
     */
    public function tokenizer(): null|TokenizerInterface
    {
        return $this->tokenizer;
    }
    
    /**
     * Generates a token.
     * 
     * @return string The generated token.
     */
    public function generateToken(): string
    {
        if (is_null($this->tokenizer)) {
            return '';
        }
        
        return $this->tokenizer->generate($this->tokenizer->getTokenName());
    }
    
    /**
     * Generates a token input element.
     * 
     * @return string The generated token input.
     */
    public function generateTokenInput(): string
    {
        if (is_null($this->tokenizer)) {
            return '';
        }
        
        return $this->input(            
            name: $this->tokenizer->getTokenInputName(),
            type: 'hidden',
            value: $this->generateToken(),
            withInput: false,
        );
    }
    
    /**
     * Generates a name to array if name has the array notation defined.
     * 
     * @param string $name The name.
     * @return string The generated name.
     */
    public function nameToArray(string $name): string
    {
        if (! $this->hasArrayNotation($name)) {
            return $name;
        }
        
        $nameArr = explode($this->arrayNotation, $name);
        $name = $nameArr[0]; 
        unset($nameArr[0]);

        foreach ($nameArr as $key) {
            $name .= '['.$key.']';
        }

        return $name;
    }

    /**
     * Generates a name as array to a notation based name.
     *
     * @param string $name The name.
     * @return string The generated name.
     */
    public function nameToNotation(string $name): string
    {
        return str_replace(['[]', '[', ']'], ['', $this->arrayNotation, ''], $name);
    }
    
    /**
     * Check if the specified name contains a notation.
     *
     * @param string $name The name.     
     * @return bool True is array, false not an array.
     */
    public function hasArrayNotation(string $name): bool
    {
        return str_contains($name, $this->arrayNotation);
    }
        
    /**
     * Generates a name to a valid id.
     *
     * @param string $name The name.
     * @return string The generated name id.
     */
    public function nameToId(string $name): string
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '_', ''], $name);
    }
    
    /**
     * Execute a callback over each item.
     *
     * @param iterable $items
     * @param Closure $callback
     * @return Each
     */
    public function each(iterable $items, Closure $callback): Each
    {
        return new Each($items, $callback);
    }

    /**
     * Returns the input data for the specified name.
     * 
     * @param string $name
     * @param mixed $default A default value.
     * @param bool $withInput If the value should be repopulated with the input data.
     * @return mixed The value.
     */
    public function getInput(string $name, mixed $default = null, bool $withInput = true): mixed
    {
        if ($withInput === false) {
            return $default;
        }

        if ($this->input) {
            $default = $this->input->get($this->nameToNotation($name), $default);
        }
        
        return $default;
    }

    /**
     * Check if there is an input by name.
     *
     * @param string $name The name.
     * @return bool True has input, false has not.
     */
    public function hasInput(string $name): bool
    {
        return !is_null($this->input)
            ? $this->input->has($this->nameToNotation($name))
            : false;
    }
    
    /**
     * Generates group name based on the specified name and element type.
     *
     * @param string $name The name.
     * @param string $elementType The element type.
     * @return string The generated group name.
     */
    protected function nameToGroup(string $name, string $elementType): string
    {
        $group = $this->nameToNotation($name);
        
        if (!in_array($elementType, ['input.radio', 'input.checkbox'])) {
            return $group;
        }
        
        if (str_ends_with($name, '[]')) {
            return $group;
        }
        
        if (!$this->hasArrayNotation($group)) {
            return $group;
        }
        
        $groupArr = explode($this->arrayNotation, $group);
        
        array_pop($groupArr);
                
        return implode($this->arrayNotation, $groupArr);
    }    
    
    /**
     * Generates optgroup element. 
     *
     * @param iterable $options
     * @param iterable $items
     * @param string|int $label
     * @param array $attributes
     * @param array $optionAttributes
     * @param mixed $selected
     * @param null|string $name
     * @return string
     */
    protected function optgroup(
        iterable $options,
        iterable $items,
        string|int $label,
        array $attributes = [],
        array $optionAttributes = [],
        mixed $selected = null,
        null|string $name = null        
    ): string {
        
        $html = '';
        
        foreach($items as $key => $item)
        {
            if (is_iterable($item)) {
                // future version might support nested groups.
                $html .= $this->optgroup($options, $item, $key, $attributes, $optionAttributes, $selected, $name);
            } else {
                [$value, $optionLabel] = $this->ensureItem($options, $item, $key);
                
                $attr = array_merge(
                    $optionAttributes['*'] ?? [],
                    $optionAttributes[$value] ?? []
                );
                
                $html .= $this->option($value, $optionLabel, $attr, $selected, $name);
            }
        }
        
        $attributes['label'] = (string)$label;
        
        return '<optgroup'.$this->formatAttributes($attributes).'>'.$html.'</optgroup>';
    }
    
    /**
     * Formats the attributes.
     * 
     * @param array $attributes The attributes.
     * @return string The formatted attributes.
     */
    protected function formatAttributes(array $attributes): string
    {
        if (empty($attributes)) {
            return '';
        }

        $formatted = [];

        foreach($attributes as $name => $value) {
            
            if (is_int($name)) {
                $formatted[] = $this->esc($value);
                continue;
            }
            
            if (is_null($value) || $value === '') {
                $formatted[] = $this->esc($name);
                continue;
            }
            
            if (is_array($value)) {
                
                if ($name === 'class') {
                    $formatted[] = $this->esc($name).'="'.$this->esc(implode(' ', array_unique($value))).'"';
                } else {                    
                    $formatted[] = $this->esc($name)."='".$this->esc(json_encode($value))."'";
                }
                
            } else {
                $formatted[] = $this->esc($name).'="'.$this->esc($value).'"';
            }
        }

        return ' '.implode(' ', $formatted);
    }    

    /**
     * Escapes string with htmlspecialchars.
     * 
     * @param string|Stringable $string
     * @param int $flags
     * @param string $encoding
     * @param bool $double_encode
     * @return string
     */
    protected function esc(
        string|Stringable $string,
        int $flags = ENT_QUOTES,
        string $encoding = 'UTF-8',
        bool $double_encode = true
    ): string {
        
        if ($string instanceof Stringable) {
            $string = $string->__toString();
        }
        
        return htmlspecialchars($string, $flags, $encoding, $double_encode);
    }
    
    /**
     * Ensures the item.
     *
     * @param iterable $items
     * @param mixed $item
     * @param int|string $key
     * @return array
     */
    protected function ensureItem(iterable $items, mixed $item, int|string $key): array
    {
        if ($items instanceof Each) {
            [$key, $item, $index] = array_pad(
                call_user_func_array($items->callback(), [$item, $key]),
                3,
                null
            );
        }
        
        $value = is_scalar($key) ? (string)$key : '';
        $label = is_scalar($item) ? (string)$item : (is_null($item) ? $item : $value);
        $index = $index ?? null;
        
        return [$value, $label, $index];
    }
}