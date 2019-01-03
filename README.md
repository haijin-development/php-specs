# Haijin Specs

A testing framework to replace PHPUnit using a simple DSL inspired by RSpec.

[![Latest Stable Version](https://poser.pugx.org/haijin/specs/version)](https://packagist.org/packages/haijin/specs)
[![Latest Unstable Version](https://poser.pugx.org/haijin/specs/v/unstable)](https://packagist.org/packages/haijin/specs)
[![Build Status](https://travis-ci.com/haijin-development/php-specs.svg?branch=v0.0.2)](https://travis-ci.com/haijin-development/php-specs)
[![License](https://poser.pugx.org/haijin/specs/license)](https://packagist.org/packages/haijin/specs)

### Version 0.0.1

This library is under active development and no stable version was released yet.

If you like it a lot you may contribute by [financing](https://github.com/haijin-development/support-haijin-development) its development.

## Table of contents

1. [Installation](#c-1)
2. [Usage](#c-2)
    1. [Spec definitions](#c-2-1)
    2. [Built-in expectations](#c-2-2)
    3. [Specs structure](#c-2-3)
    4. [Evaluating code before and after running expectations](#c-2-4)
    5. [Defining values with ->let(...)](#c-2-5)
    6. [Defining custom expectations](#c-2-6)
        1. [Expectation definition structure](#c-2-6-1)
        2. [Getting the value being validated](#c-2-6-2)
        3. [Parameters of the definition closures](#c-2-6-3)
        4. [Raising expectation errors](#c-2-6-4)
        5. [Complete example](#c-2-6-5)
3. [Running the specs](#c-3)

<a name="c-1"></a>
## Installation

Include this library in your project `composer.json` file:

```json
{
    ...

    "require-dev": {
        ...
        "haijin/specs": "^0.0.1",
        ...
    },

    ...
}
```
<a name="c-2"></a>
## Usage

In the project create a subfolder named `specs`.

In any nested subfolder of `specs` create files with specs definitions.

<a name="c-2-1"></a>
### Spec definitions

A spec file contains expectations on a feature or functionality.

A spec file looks like this:

```php
<?php

$spec->describe( "When formatting a user's full name", function() {

    $this->it( "appends the user's last name to the user's name", function() {

        $user = new User( "Lisa", "Simpson" );

        $this->expect( $user->get_full_name() ) ->to() ->equal( "Lisa Simpson" );

    });

});
```

<a name="c-2-2"></a>
### Built-in expectations

Expectations are the equivalent to the assertions used in PHPUnit but more expressive and more clear.

A expectation has two main parts, the value on which expectations are being expressed, for instance a string with the user's full name:

```php
$this->expect( $user->get_full_name() )
```

and the expectations on that value:

```php
->to() ->equal( "Lisa Simpson" );
```

Specs library comes with the most common expectations built-in:

```php
// Comparisson expectations

$this->expect( $value ) ->to() ->equal( $another_value );
$this->expect( $value ) ->to() ->be( ">" ) ->than($another_value);
$this->expect( $value ) ->to() ->be( "===" ) ->than($another_value);

// Types expectations

$this->expect( $value ) ->to() ->be_null();
$this->expect( $value ) ->to() ->be_string();
$this->expect( $value ) ->to() ->be_int();
$this->expect( $value ) ->to() ->be_double();
$this->expect( $value ) ->to() ->be_number();
$this->expect( $value ) ->to() ->be_kind_of( SomeClass::class );
$this->expect( $value ) ->to() ->be_instance_of( SomeClass::class );
$this->expect( $value ) ->to() ->be_true();
$this->expect( $value ) ->to() ->be_false();

// String expectations

$this->expect( $string_value ) ->to() ->begin_with( $substring );
$this->expect( $string_value ) ->to() ->end_with( $substring );
$this->expect( $string_value ) ->to() ->contain( $substring );
$this->expect( $string_value ) ->to() ->match( $regexp );

// Exceptions

$this->expect_closure( function() {

    throw Exception();

}) ->to_raise( Exception::class );


$this->expect_closure( function() {

        throw Exception( "Some message." );

}) ->to_raise( Exception::class, function($e) {

    $this->expect( $e->getMessage() ) ->to() ->equal( "Some message." );        

});
```

Any expectation can also be negated with

```php
// Comparisson expectations

$this->expect( $value ) ->not() ->to() ->equal( $another_value );

// Exceptions

$this->expect_closure( function() {

    throw Exception();

}) ->not() ->to_raise( Exception::class );
```

<a name="c-2-3"></a>
### Specs structure

A spec begins with a `$spec->decribe(...)` statement, and can include any number of additional nested `$this->describe()` statements. Each `describe()` statement documents a group of expectations that are somehow related, for instance because they declare different expected behaviours depending on different use cases for the same functionality.

The `->it(...)` statement is where expectations are declared.

```php
$spec->describe( "When formatting a user's full name", function() {

    $this->describe( "with both name and last name defined", function() {

        $this->it( "appends the user's last name to the user's name", function() {

            $user = new User( "Lisa", "Simpson" );

            $this->expect( $user->get_full_name() ) ->to() ->equal( "Lisa Simpson" );

        });

    });

    $this->describe( "with the name undefined", function() {

        $this->it( "returns only the last name", function() {

            $user = new User( "", "Simpson" );

            $this->expect( $user->get_full_name() ) ->to() ->equal( "Simpson" );

        });

    });

    $this->describe( "with the last name undefined", function() {

        $this->it( "returns only the name", function() {

            $user = new User( "Lisa", "" );

            $this->expect( $user->get_full_name() ) ->to() ->equal( "Lisa" );

        });

    });
});
```

<a name="c-2-4"></a>
### Evaluating code before and after running expectations

<a name="c-2-5"></a>
### Defining values with $this->let(...)

<a name="c-2-6"></a>
### Defining custom expectations

<a name="c-2-6-1"></a>
#### Expectation definition structure

Expectation definitions have 4 parts, each defined with a closure.

The first one is the `$this->before($closure)` closure. This closure is evaluated before evaluating an expectation on a value. This block is optional but it can be used to perform complex calculations needed by the expectations for both the assertive and the negated closures.

The second one is the `$this->assert_with($closure)` closure. This closure is evaluated to evaluate a possitive expectation on a value.

The third one is the `$this->negate_with($closure)` closure. This closure is evaluated to evaluate a nagated expectation on a value.

The fourth one is the `$this->after($closure)` closure. This closure is evaluated after the expectation is run, even when an ExpectationError was raise. This closure is optional but it can be used to release resources allocated during the evaluation of the previous closures.

<a name="c-2-6-2"></a>
#### Getting the value being validated

To get the actual value being validated, use `$this->actual_value`.

<a name="c-2-6-3"></a>
#### Parameters of the definition closures

The parameters of the 4 closures are the ones passed to the expectation in the Spec. For instance, if the spec is declared as

```php
$this->expect( 1 ) ->not() ->to() ->equal( 2 );
```

the parameters for the 4 closures of the the `equal` expectation will be the expected value `2`:

```php
$this->before( function($expected_value) {
});

$this->assert_with( function($expected_value) {
});

$this->negate_with( function($expected_value) {
});

$this->after( function($expected_value) {
});
```

<a name="c-2-6-4"></a>
#### Raising expectation errors

To raise an expectation error use `$this->raise_error( $error_message )`.

<a name="c-2-6-5"></a>
#### Complete example

Here is a complete example of a custom validation:

```php

Expectations::define_expectation( "equal", function() {

    $this->before( function($expected_value) {
        $this->got_expected_value = $expected_value == $this->actual_value;
    });

    $this->assert_with( function($expected_value) {

        if( $this->got_expected_value ) {
            return;
        }

        $this->raise_error(
            "Expected value to equal {$expected_value}, got {$this->actual_value}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_error(
            "Expected value not to equal {$expected_value}, got {$this->actual_value}."
        );
    });

    $this->after( function($expected_value) {
    });
});
```
<a name="c-3"></a>
## Running the specs

```
composer specs
```