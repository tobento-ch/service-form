# Form Service

Buliding HTML forms.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
- [Documentation](#documentation)
    - [Create Form](#create-form)
    - [Form Factory](#form-factory)
    - [Form Elements](#form-elements)
        - [Form](#form)
        - [Input](#input)
            - [Checkbox Type](#checkbox-type)
            - [Radio Type](#radio-type)
        - [Label](#label)
        - [Select](#select)
        - [Textarea](#textarea)
        - [Button](#button)
        - [Fieldset And Legend](#fieldset-and-legend)
        - [Datalist](#datalist)
        - [Option](#option)
    - [Input Data](#input-data)
    - [Tokenizer](#tokenizer)
        - [Session Tokenizer](#session-tokenizer)
        - [Tokenizer Methods](#tokenizer-methods)
        - [Tokenizer PSR-15 Middleware](#tokenizer-psr-15-middleware)
        - [Form Tokenizer Methods](#form-tokenizer-methods)
    - [Messages](#messages)
    - [CSRF Protection](#csrf-protection)
    - [Method Spoofing](#method-spoofing)
    - [Active Form Elements](#active-form-elements)
    - [Form Helper Methods](#form-helper-methods)
- [Credits](#credits)
___

# Getting started

Add the latest version of the html service project running this command.

```
composer require tobento/service-form
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Create Form

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Form\InputInterface;
use Tobento\Service\Form\TokenizerInterface;
use Tobento\Service\Form\ActiveElementsInterface;
use Tobento\Service\Message\MessagesInterface;

$form = new Form(
    input: null, // null|InputInterface
    tokenizer: null, // null|TokenizerInterface
    activeElements: null, // null|ActiveElementsInterface
    messages: null // null|MessagesInterface
);
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **input** | The input data to repopulate values. See [Input Data](#input-data) for more detail. |
| **tokenizer** | See [Tokenizer](#tokenizer) for more detail. |
| **activeElements** | See [Active Form Elements](#active-form-elements) for more detail. |
| **messages** | Used to display messages. See [Messages](#messages) for more detail. |


## Form Factory

You may use form factories to create the form class.

**ResponserFormFactory**

Firstly, make sure you have the responser service installed:

```
composer require tobento/service-responser
```

Check out the [Responser Service](https://github.com/tobento-ch/service-responser) to learn more about it in general.

```php
use Tobento\Service\Form\FormFactoryInterface;
use Tobento\Service\Form\ResponserFormFactory;
use Tobento\Service\Form\ActiveElementsInterface;
use Tobento\Service\Form\Form;
use Tobento\Service\Responser\ResponserInterface;

$formFactory = new ResponserFormFactory(
    responser: $responser, // ResponserInterface
    tokenizer: null, // null|TokenizerInterface
    activeElements: null, // null|ActiveElementsInterface
);

var_dump($formFactory instanceof FormFactoryInterface);
// bool(true)


$form = $formFactory->createForm();

var_dump($form instanceof Form);
// bool(true)
```

## Form Elements

### Form

```php
<?= $form->form() ?>
// <form method="POST">

<?= $form->close() ?>
// </form>
```

**form attributes**

```php
<?= $form->form(attributes: ['method' => 'GET']) ?>
// <form method="GET">
```

### Input

```php
<?= $form->input(
    name: 'name',
    type: 'text',
    value: 'value',
    attributes: [],
    selected: null,
    withInput: true,
) ?>
// <input name="name" id="name" type="text" value="value">
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$name** | The name of the input element. |
| string **$type** = 'text'| The input type such as text, hidden, checkbox, ... |
| null\|string **$value** = null | Any value. |
| array **$attributes** = [] | Any input element attributes. For instance, ['class' => 'class-name']. |
| mixed **$selected** = null | The selected value(s) for checkbox or radio type. |
| bool **$withInput** = true | If the value should be repopulated with the input data. |

#### Checkbox Type

```php
<?= $form->input(
    name: 'colors[]',
    type: 'checkbox',
    value: 'red',
    attributes: ['id' => 'colors_red'],
    selected: ['red'], // or 'red'
) ?>
// <input id="colors_red" name="colors[]" type="checkbox" value="red" checked>
```

**checkboxes**

You may use the checkboxes method to create multiple checkbox input elements with its labels:

```php
<?= $form->checkboxes(
    name: 'colors',
    items: ['red' => 'Red', 'blue' => 'Blue'],
    selected: ['blue'],
    attributes: [],
    labelAttributes: [],
    withInput: true,
    wrapClass: 'form-wrap-checkbox'
) ?>
/*
<span class="form-wrap-checkbox">
    <input id="colors_1" name="colors[]" type="checkbox" value="red">
    <label for="colors_1">Red</label>
</span>
<span class="form-wrap-checkbox">
    <input id="colors_2" name="colors[]" type="checkbox" value="blue" checked>
    <label for="colors_2">Blue</label>
</span>
*/
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$name** | The name of the input element. |
| iterable **$items** = [] | The items. |
| array **$selected** = [] | The selected checkbox values. |
| array **$attributes** = [] | Any attributes for the checkbox element. For instance, ['class' => 'class-name'] |
| array **$labelAttributes** = [] | Any attributes for the label element. For instance, ['class' => 'class-name'] |
| bool **$withInput** = true | If the value should be repopulated with the input data. |
| string **$wrapClass** = 'form-wrap-checkbox' | The class for the wrap element. |

**checkboxes with each**

You may use the **each** method if you have an array of objects or just to have more control over the value, label and array key for the name of the checkbox.

```php
$items = ['red' => 'Red', 'blue' => 'Blue'];

<?= $form->checkboxes(
    name: 'colors',
    items: $form->each(items: $items, callback: function($item, $key): array {
        // value:string, label:string|null, array-key:string|null
        return [$key, strtoupper($item), $key];
    }),
    selected: ['blue'],
) ?>
/*
<span class="form-wrap-checkbox">
    <input id="colors_red" name="colors[red]" type="checkbox" value="red">
    <label for="colors_red">RED</label>
</span>
<span class="form-wrap-checkbox">
    <input id="colors_blue" name="colors[blue]" type="checkbox" value="blue" checked>
    <label for="colors_blue">BLUE</label>
</span>
*/
```

#### Radio Type

```php
<?= $form->input(
    name: 'colors',
    type: 'radio',
    value: 'red',
    attributes: ['id' => 'colors_red'],
    selected: 'red',
) ?>
// <input id="colors_red" name="colors" type="radio" value="red" checked>
```

**radios**

You may use the radios method to create multiple radio input elements with its labels:

```php
<?= $form->radios(
    name: 'colors',
    items: ['red' => 'Red', 'blue' => 'Blue'],
    selected: 'blue',
    attributes: [],
    labelAttributes: [],
    withInput: true,
    wrapClass: 'form-wrap-radio'
) ?>
/*
<span class="form-wrap-radio">
    <input id="colors_1" name="colors[]" type="radio" value="red">
    <label for="colors_1">Red</label>
</span>
<span class="form-wrap-radio">
    <input id="colors_2" name="colors[]" type="radio" value="blue" checked>
    <label for="colors_2">Blue</label>
</span>
*/
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$name** | The name of the input element. |
| iterable **$items** = [] | The items. |
| null\|string **$selected** = null | The selected radio value. |
| array **$attributes** = [] | Any attributes for the radio element. For instance, ['class' => 'class-name'] |
| array **$labelAttributes** = [] | Any attributes for the label element. For instance, ['class' => 'class-name'] |
| bool **$withInput** = true | If the value should be repopulated with the input data. |
| string **$wrapClass** = 'form-wrap-radio' | The class for the wrap element. |

**radios with each**

You may use the **each** method if you have an array of objects or just to have more control over the value and label.

```php
$items = ['red' => 'Red', 'blue' => 'Blue'];

<?= $form->radios(
    name: 'colors',
    items: $form->each(items: $items, callback: function($item, $key): array {
        // value:string, label:string|null
        return [$key, strtoupper($item)];
    }),
    selected: 'blue',
) ?>
/*
<span class="form-wrap-radio">
    <input id="colors_red" name="colors" type="radio" value="red">
    <label for="colors_red">RED</label>
</span>
<span class="form-wrap-radio">
    <input id="colors_blue" name="colors" type="radio" value="blue" checked>
    <label for="colors_blue">BLUE</label>
</span>
*/
```

### Label

```php
<?= $form->label(
    text: 'Text',
    for: 'colors',
    attributes: [],
    requiredText: '',
    optionalText: '',
) ?>
// <label for="colors">Text</label>
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$text** | The label text. |
| null\|string **$for** = null | The for attribute should be equal to the id attribute of the related element to bind them together. |
| array **$attributes** = [] | Any attributes for the label element. For instance, ['class' => 'class-name'] |
| string **$requiredText** = '' | Any required text. |
| string **$optionalText** = '' | Any optional text. |

**with required text**

```php
<?= $form->label(
    text: 'Text',
    requiredText: 'required',
) ?>
// <label>Text<span class="required">required</span></label>
```

**with optional text**

```php
<?= $form->label(
    text: 'Text',
    optionalText: 'optional',
) ?>
// <label>Text<span class="optional">optional</span></label>
```

### Select

```php
<?= $form->select(
    name: 'colors[]',
    items: ['red' => 'Red', 'blue' => 'Blue'],
    selected: ['blue'],
    selectAttributes: ['multiple'],
    optionAttributes: [],
    optgroupAttributes: [],
    emptyOption: null,
    withInput: true,
) ?>
/*
<select multiple name="colors" id="colors">
    <option value="red">Red</option>
    <option value="blue" selected>Blue</option>
</select>
*/
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$name** | The name of the select element. |
| iterable **$items** = [] | The items. Multidimensional array for optgroups. |
| mixed **$selected** = null | The selected value(s). Use array for multiple selected, but do not forget to set the multiple attribute on the select element and the select name should end with '[]'. |
| array **$selectAttributes** = [] | Any attributes for the select element. For instance, ['class' => 'class-name'] |
| array **$optionAttributes** = [] | Any attributes for a single option. For instance, ['red' => ['class' => 'class-name']]. You may use asterix sign for all option elements, ['*' => ['class' => 'class-name']] |
| array **$optgroupAttributes** = [] | Any attributes for the optgroup elements. For instance, ['class' => 'class-name'] |
| null\|array **$emptyOption** = null | Adds an empty option with the specified value and label. |
| bool **$withInput** = true | If the value should be repopulated with the input data. |

**with optgroup elements**

```php
<?= $form->select(
    name: 'roles',
    items: [
        'Frontend' => [
            'guest' => 'Guest',
            'registered' => 'Registered',
        ],
        'Backend' => [
            'editor' => 'Editor',
            'administrator' => 'Aministrator',
        ],        
    ],
) ?>
/*
<select name="roles" id="roles">
    <optgroup label="Frontend">
        <option value="guest">Guest</option>
        <option value="registered">Registered</option>
    </optgroup>
    <optgroup label="Backend">
        <option value="editor">Editor</option>
        <option value="administrator">Aministrator</option>
    </optgroup>
</select>
*/
```

**with each method**

You may use the **each** method if you have an array of objects or just to have more control over the value, label and array key for the name of the radio.

```php
$items = ['red' => 'Red', 'blue' => 'Blue'];

<?= $form->select(
    name: 'colors',
    items: $form->each(items: $items, callback: function($item, $key): array {
        // value:string, label:string|null
        return [$key, strtoupper($item)];
    }),
    selected: 'blue',
) ?>
/*
<select name="colors" id="colors">
    <option value="red">RED</option>
    <option value="blue" selected>BLUE</option>
</select>
*/
```

**with empty option**

```php
<?= $form->select(
    name: 'colors',
    items: ['red' => 'Red', 'blue' => 'Blue'],
    emptyOption: ['none', '---'],
) ?>
/*
<select name="colors" id="colors">
    <option value="none">---</option>
    <option value="red">Red</option>
    <option value="blue">Blue</option>
</select>
*/
```

### Textarea

```php
<?= $form->textarea(
    name: 'description',
    value: 'Lorem ipsum',
    attributes: [],
    withInput: true,
) ?>
// <textarea name="description" id="description">Lorem ipsum</textarea>
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$name** | The name of the textarea element. |
| null\|string **$value** = null | The value if any. |
| array **$attributes** = [] | Any attributes for the textarea element. For instance, ['class' => 'class-name'] |
| bool **$withInput** = true | If the value should be repopulated with the input data. |

### Button

```php
<?= $form->button(
    text: 'Submit Text',
    attributes: [],
    escText: true,
) ?>
// <button type="submit">Submit Text</button>
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$text** | The text for the button. |
| array **$attributes** = [] | Any attributes for the textarea element. For instance, ['class' => 'class-name'] |
| bool **$escText** = true | True escaping text, otherwise not. |

### Fieldset And Legend

```php
<?= $form->fieldset(
    legend: 'Legend',
    attributes: [],
    legendAttributes: [],
) ?>
// <fieldset><legend>Legend</legend>

<?= $form->fieldsetClose() ?>
// </fieldset>
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$legend** | The text for legend element. |
| array **$attributes** = [] | Any attributes for the fieldset element. For instance, ['class' => 'class-name'] |
| array **$legendAttributes** = [] | Any attributes for the legend element. For instance, ['class' => 'class-name'] |

### Datalist

```php
<?= $form->datalist(
    name: 'colors',
    items: ['red', 'blue'],
    attributes: [],
) ?>
/*
<datalist id="colors">
    <option value="red"></option>
    <option value="blue"></option>
</datalist>
*/
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$name** | The name (id) of the datalist. |
| iterable $items = [] | Any items. |
| array **$attributes** = [] | Any attributes for the datalist element. For instance, ['class' => 'class-name'] |

**with each method**

You may use the **each** method if you have an array of objects or just to have more control over the value.

```php
$items = ['red' => 'Red', 'blue' => 'Blue'];

<?= $form->datalist(
    name: 'colors',
    items: $form->each(items: $items, callback: function($item, $key): array {
        // value:string
        return [strtoupper($item)];
    })
) ?>
/*
<datalist id="colors">
    <option value="RED"></option>
    <option value="BLUE"></option>
</datalist>
*/
```

### Option

```php
<?= $form->option(
    value: 'red',
    text: 'Red',
    attributes: [],
    selected: ['red'], // or 'red'
) ?>
// <option value="red" selected>Red</option>
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| string **$value** | The value of the option. |
| null\|string **$text** = null | The option text if any. |
| array **$attributes** = [] | Any attributes for the option element. For instance, ['class' => 'class-name'] |
| mixed **$selected** = null | The selected value(s). |
| null\|string **$name** = null | If set, it is used as the name for the [Active Form Elements](#active-form-elements). |

## Input Data

The input data is used to repopulate the values of the form elements.

**Create Input**

```php
use Tobento\Service\Form\InputInterface;
use Tobento\Service\Form\Input;

$input = new Input(array_merge(
    $_GET ?? [],
    $_POST ?? [],
));

var_dump($input instanceof InputInterface);
// bool(true)
```

Now, the value of the form elements gets automatically repopulated if a input data for the specified element exists.

### Form Input Methods

You may use the following methods to access the input data on the form class.

**getInput**

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Form\Input;

$form = new Form(
    input: new Input(['color' => 'red']),
);

$value = $form->getInput(
    name: 'color',
    default: 'blue',
    withInput: true, // default
);

var_dump($value);
// string(3) "red"

$value = $form->getInput(
    name: 'color',
    default: 'blue',
    withInput: false,
);

var_dump($value);
// string(4) "blue"
```

**hasInput**

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Form\Input;

$form = new Form(
    input: new Input(['color' => 'red']),
);

var_dump($form->hasInput('color'));
// bool(true)
```

**withInput**

Returns a new Form instance with the specified input.

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Form\Input;
use Tobento\Service\Form\InputInterface;

$form = new Form(
    input: new Input(['color' => 'red']),
);

$form = $form->withInput(
    input: null, // null|InputInterface
);
```

## Tokenizer

The tokenizer is used to generate and verify tokens to protect your application from cross-site request forgeries.

### Session Tokenizer

Firstly, make sure you have the session service installed:

```
composer require tobento/service-session
```

Check out the [Session Service](https://github.com/tobento-ch/service-session) to learn more about it in general.

```php
use Tobento\Service\Form\TokenizerInterface;
use Tobento\Service\Form\SessionTokenizer;
use Tobento\Service\Session\SessionInterface;
use Tobento\Service\Session\Session;

$session = new Session(name: 'sess');

$tokenizer = new SessionTokenizer(
    session: $session, // SessionInterface
    tokenName: 'csrf', // default
    tokenInputName: '_token' // default
);

var_dump($tokenizer instanceof TokenizerInterface);
// bool(true)
```

### Tokenizer Methods

**setTokenName**

```php
$tokenizer->setTokenName('csrf');
```

**getTokenName**

```php
var_dump($tokenizer->getTokenName());
// string(4) "csrf"
```

**setTokenInputName**

```php
$tokenizer->setTokenInputName('_token');
```

**getTokenInputName**

```php
var_dump($tokenizer->getTokenInputName());
// string(6) "_token"
```

**get**

Return the token for the specified name.

```php
var_dump($tokenizer->get('csrf'));
// Null or string
```

**generate**

Generates and returns a new token for the specified name.

```php
var_dump($tokenizer->generate('csrf'));
// string(40) "token-string..."
```

**delete**

Delete the token for the specified name.

```php
$tokenizer->delete('csrf');
```

**verifyToken**

```php
$isValid = $tokenizer->verifyToken(
    inputToken: 'input token string', // string
    name: 'csrf', // null|string The name of the token to verify.
);

// or set a token.
$isValid = $tokenizer->verifyToken(
    inputToken: 'input token string', // string
    token: 'token string' // null|string
);
```

### Tokenizer PSR-15 Middleware

You may also use the VerifyCsrfToken::class middleware to verify the form token.\
If token is invalid, a InvalidTokenException will be thrown.

```php
use Tobento\Service\Form\Middleware\VerifyCsrfToken;
use Tobento\Service\Form\InvalidTokenException;

$middleware = new VerifyCsrfToken(
    tokenizer: $tokenizer, // TokenizerInterface
    name: 'csrf', // default
    inputName: '_token', // default
    headerTokenName: 'X-Csrf-Token', // default. Null if not to use at all.
    onetimeToken: false, // default
);
```

You may wish to exclude a set of URIs from CSRF protection:

```php
$request = $request->withAttribute(
    VerifyCsrfToken::EXCLUDE_URIS_KEY,
    [
        'http://example.com/foo/bar',
    ]
);
```

### Form Tokenizer Methods

**tokenizer**

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Form\TokenizerInterface;

$form = new Form(
    tokenizer: $tokenizer,
);

var_dump($form->tokenizer() instanceof TokenizerInterface);
// bool(true)
```

**generateToken**

```php
use Tobento\Service\Form\Form;

$form = new Form(
    tokenizer: $tokenizer,
);

var_dump($form->generateToken());
// string(40) "token string..."
```

**generateTokenInput**

```php
use Tobento\Service\Form\Form;

$form = new Form(
    tokenizer: $tokenizer,
);

echo $form->generateTokenInput();
// <input name="_token" type="hidden" value="token string...">
```

## Messages

Messages are used to display any message type on the form elements.

Check out the [Message Service](https://github.com/tobento-ch/service-message) to learn more about it in general.

```php
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\Messages;

$messages = new Messages();

var_dump($messages instanceof MessagesInterface);
// bool(true)
```

### Form Messages Methods

**messages**

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Message\MessagesInterface;

$form = new Form();

var_dump($form->messages() instanceof MessagesInterface);
// bool(true)
```

**withMessages**

Returns a new instance with the specified messages.

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Message\MessagesInterface;

$form = new Form();

$form = $form->withMessages(
    input: null, // null|MessagesInterface
);
```

**getMessage**

Returns the message for the specified key.

```php
use Tobento\Service\Form\Form;

$form = new Form();

var_dump($form->getMessage('key'));
// string(0) ""
```

**getRenderedMessageKeys**

Returns the rendered message keys.

```php
use Tobento\Service\Form\Form;

$form = new Form();

var_dump($form->getRenderedMessageKeys());
// array(0) { }
```

### Adding Messages

An example of how to add messages for a specific form element.

```php
use Tobento\Service\Form\Form;

$form = new Form();

$form->messages()->add(
    level: 'error',
    message: 'Please accept our terms.',
    key: 'terms',
);

<?= $form->input('terms', 'checkbox', 'terms') ?>
// <span class="form-message error">Please accept our terms.</span>
// <input name="terms" id="terms" type="checkbox">
```

You may check out the [Validation Service](https://github.com/tobento-ch/service-validation) for validating the form and passing the validator messages to the form.

## CSRF Protection

If you have specified a tokenizer on the Form class, the form method will automatically add a hidden input element with the token.

```php
<?= $form->form() ?>
// <form method="POST">
// <input name="_token" type="hidden" value="generated-token-string">
```

## Method Spoofing

If you set PUT, PATCH or DELETE as the method, a hidden input element named **_method** will be automatically added for spoofing.

```php
<?= $form->form(['method' => 'PUT']) ?>
// <form method="POST">
// <input name="_method" type="hidden" value="PUT">
```

## Active Form Elements

**getActiveElements**

Returns the active elements.

```php
use Tobento\Service\Form\Form;
use Tobento\Service\Form\ActiveElements;
use Tobento\Service\Form\ActiveElementsInterface;

$form = new Form(
    activeElements: new ActiveElements(),
);

var_dump($form->getActiveElements() instanceof ActiveElementsInterface);
// bool(true)
```

**isActive**

If the element is active.

```php
use Tobento\Service\Form\Form;

$form = new Form();

$isActive = $form->isActive(
    name: 'colors',
    value: 'red', // the value to be active.
    default: null,
);
```

## Form Helper Methods

**nameToArray**

Generates the specified name to an array string if name contains notation syntax.

```php
use Tobento\Service\Form\Form;

$form = new Form();

var_dump($form->nameToArray('user.firstname'));
// string(15) "user[firstname]"
```

**nameToNotation**

Generates the specified name from an array string to a notation based name.

```php
use Tobento\Service\Form\Form;

$form = new Form();

var_dump($form->nameToNotation('user[firstname]'));
// string(14) "user.firstname"
```

**nameToId**

Generates the specified name to a valid id.

```php
use Tobento\Service\Form\Form;

$form = new Form();

var_dump($form->nameToId('user[firstname]'));
// string(14) "user_firstname"
```

**hasArrayNotation**

Check if the specified name contains a notation.

```php
use Tobento\Service\Form\Form;

$form = new Form();

var_dump($form->hasArrayNotation('user.firstname'));
// bool(true)
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)