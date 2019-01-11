# Haijin Specs

A testing framework to replace PHPUnit using a simple DSL inspired by RSpec.

[![Latest Stable Version](https://poser.pugx.org/haijin/specs/version)](https://packagist.org/packages/haijin/specs)
[![Latest Unstable Version](https://poser.pugx.org/haijin/specs/v/unstable)](https://packagist.org/packages/haijin/specs)
[![Build Status](https://travis-ci.org/haijin-development/php-specs.svg?branch=master)](https://travis-ci.org/haijin-development/php-specs)
[![License](https://poser.pugx.org/haijin/specs/license)](https://packagist.org/packages/haijin/specs)

### Version 0.0.1 (beta)

This library is under active development.

If you like it a lot you may contribute by [financing](https://github.com/haijin-development/support-haijin-development) its development.

## Table of contents

1. [Installation](#c-1)
2. [Usage](#c-2)
    1. [Spec definitions](#c-2-1)
    2. [Built-in expectations](#c-2-2)
        1. [expect( $object ) ->to() ->be() ->like(...)](#c-2-2-1)
        2. [expect( $object ) ->to() ->be() ->exactly_like(...)](#c-2-2-2)
    3. [Specs structure](#c-2-3)
    4. [Evaluating code before and after running expectations](#c-2-4)
    5. [Defining values with let(...) expressions](#c-2-5)
    6. [Defining methods with def(...)](#c-2-6)
    7. [Defining custom expectations](#c-2-7)
        1. [Expectation definition structure](#c-2-7-1)
        2. [Getting the value being validated](#c-2-7-2)
        3. [Parameters of the definition closures](#c-2-7-3)
        4. [Raising expectation errors](#c-2-7-4)
        5. [Evaluating closures within custom expectations](#c-2-7-5)
        6. [Complete example](#c-2-7-6)
    7. [Temporary skipping a spec](#c-2-8)
    8. [Running the specs from the command line](#c-2-9)
3. [Running this project tests](#c-3)

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

In the project folder run

```
composer install

php ./vendor/bin/specs init
```

This will create a folder named `tests/specs` with a `specs_boot.php` sample file.

In any nested subfolder of `tests/specs` create files with specs definitions. No naming convention is needed for these files, all of them will be considered spec files.

`tests/specs_boot.php` is an optional regular PHP script file loaded before any spec used to customize the specs runner.

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
// Comparison expectations

$this->expect( $value ) ->to() ->equal( $another_value );
$this->expect( $value ) ->to() ->be( ">" ) ->than( $another_value );
$this->expect( $value ) ->to() ->be( "===" ) ->than( $another_value );
$this->expect( $value ) ->to() ->be() ->null();
$this->expect( $value ) ->to() ->be() ->true();
$this->expect( $value ) ->to() ->be() ->false();
$this->expect( $value ) ->to() ->be() ->like([
    "name" => "Lisa",
    "last_name" => "Simpson",
    "address" => [
        "street_name" => "Evergreen",
        "street_number" => 742
    ]
]);
$this->expect( $value ) ->to() ->be() ->exactly_like([
    "name" => "Lisa",
    "last_name" => "Simpson",
    "address" => [
        "street_name" => "Evergreen",
        "street_number" => 742
    ]
]);

// Types expectations

$this->expect( $value ) ->to() ->be() ->string();
$this->expect( $value ) ->to() ->be() ->int();
$this->expect( $value ) ->to() ->be() ->double();
$this->expect( $value ) ->to() ->be() ->number();
$this->expect( $value ) ->to() ->be() ->bool();
$this->expect( $value ) ->to() ->be() ->array();
$this->expect( $value ) ->to() ->be() ->a( SomeClass::class );
$this->expect( $value ) ->to() ->be() ->instance_of( SomeClass::class );

// String expectations

$this->expect( $string_value ) ->to() ->begin_with( $substring );
$this->expect( $string_value ) ->to() ->end_with( $substring );
$this->expect( $string_value ) ->to() ->contain( $substring );
$this->expect( $string_value ) ->to() ->match( $regexp );
$this->expect( $string_value ) ->to() ->match( $regexp, function($matches) {
    // further expectations on the $matches, for instance:
    //$this->expect( $matches[ 1 ] ) ->to() ->equal(...) ;
});

// Array expectations

$this->expect( $array_value ) ->to() ->include( $value );
$this->expect( $array_value ) ->to() ->include_all( $values );
$this->expect( $array_value ) ->to() ->include_any( $values );
$this->expect( $array_value ) ->to() ->include_none( $values );
$this->expect( $array_value ) ->to() ->include_key( $key );
$this->expect( $array_value ) ->to() ->include_key( $key, funtion($value) {
    // further expectations on the $value, for instance:
    //$this->expect( $value ) ->to() ->equal(...) ;
});
$this->expect( $array_value ) ->to() ->include_value( $value );

// File expectations

$this->expect( $file_path ) ->to() ->be() ->a_file();
$this->expect( $file_path ) ->to() ->have_file_contents( function($contents) {
    // further expectations on the $contents, for instance:
    //$this->expect( $contents ) ->to() ->match(...) ;
});

$this->expect( $file_path ) ->to() ->be() ->a_folder();
$this->expect( $file_path ) ->to() ->have_folder_contents( function($files, $files_base_path) {
    // further expectations on the $files
});

// Exceptions

$this->expect( function() {

    throw Exception();

}) ->to() ->raise( Exception::class );


$this->expect( function() {

        throw Exception( "Some message." );

}) ->to() ->raise( Exception::class, function($e) {

    $this->expect( $e->getMessage() ) ->to() ->equal( "Some message." );        

});
```

Most expectations can also be negated with

```php
$this->expect( $value ) ->not() ->to() ->equal( $another_value );

$this->expect( function() {

    throw Exception();

}) ->not() ->to() ->raise( Exception::class );
```

<a name="c-2-2-1"></a>
#### expect( $object ) ->to() ->be() ->like(...)

The expectation `expect( $object ) ->to() ->be() ->like(...)` evaluates a nested expectations on arrays, associative arrays, objects and any mix of them.

Example:

```php

$user = [
    "name" => "Lisa",
    "last_name" => "Simpson",
    "address" => [
        "street_name" => "Evergreen",
        "street_number" => 742
    ],
    "ignored_attribute" => ""
];

$this->expect( $user ) ->to() ->be() ->like([
    "name" => "Lisa",
    "last_name" => "Simpson",
    "address" => [
        "street_name" => "Evergreen",
        "street_number" => 742
    ]
]);
```

It also works with getter functions:

```php
$this->expect( $user ) ->to() ->be() ->like([
    "get_name()" => "Lisa",
    "get_last_name()" => "Simpson",
    "get_address()" => [
        "get_street_name()" => "Evergreen",
        "get_street_number()" => 742
    ]
]);
```

<a name="c-2-2-2"></a>
#### expect( $object ) ->to() ->be() ->exactly_like(...)

Same as `expect( $object ) ->to() ->be() ->like(...)` but if the object is array and has more or less attributes than the expected value the expectation fails.


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

To evaluate statements before and after each spec is run use `before_each($closure)` and `after_each($closure)` at any `describe` statement:

```php
$spec->describe( "When formatting a user's full name", function() {

    $this->before_each( function() {
        $this->n = 0;
    });

    $this->after_each( function() {
        $this->n = null;
    });

    $this->describe( "with both name and last name defined", function() {

        $this->before_each( function() {
            $this->n += 1;
        });

        $this->after_each( function() {
            $this->n -= 1;
        });


        $this->it( "...", function() {
            print $this->n;
        });

    });

});
```

To evaluate statements before and after all the specs of a `describe` statement are run use `before_all($closure)` and `after_all($closure)` statements:

```php
$spec->describe( "When formatting a user's full name", function() {

    $this->before_all( function() {
        $this->n = 0;
    });

    $this->after_all( function() {
        $this->n = null;
    });

    $this->describe( "with both name and last name defined", function() {

        $this->before_all( function() {
            $this->n += 1;
        });

        $this->after_all( function() {
            $this->n -= 1;
        });


        $this->it( "...", function() {
            print $this->n;
        });

    });

});
```

To evaluate statements before and after any spec is run, like stablishing connections to databases, creating tables or creating complex folder structures, or before and after each single statement, create or add to the `tests/specs_boot.php` file the following `SpecsRunner` configuration:

```php
\Haijin\Specs\Specs_Runner::configure( function($specs) {

    $specs->before_all( function() {

    });

    $specs->after_all( function() {

    });

    $specs->before_each( function() {

    });

    $specs->after_each( function() {

    });

});
```


It is possible to use and mix multiple `before_all`, `after_all`, `before_each` and `after_each` at any level.

<a name="c-2-5"></a>
### Defining values with let(...) expressions

Define expressions and constants using the `let( $expression_name, $closure )` statement.

Expressions defined with `let(...)` are lazily evaluated the first time they are referenced by each spec.

`let(...)` expressions are inherit by child `describe(...)` specs and can be safely overriden within the scope of a child `describe(...)`.

A `let(...)` expression can reference another `let(...)` expression.

Example:

```php
$spec->describe( "When searching for users", function() {

    $this->let( "user_id", function() {
        return 1;
    });

    $this->it( "finds the user by id", function(){

        $user = Users::find_by_id( $this->user_id );

        $this->expect( $user ) ->not() ->to() ->be_null();

    });

    $this->describe( "the retrieved user data", function() {

        $this->let( "user", function() {
            return Users::find_by_id( $this->user_id );
        });

        $this->it( "includes the name", function() {

            $this->expect( $this->user->get_name() ) ->to() ->equal( "Lisa" );

        });

        $this->it( "includes the lastname", function() {

            $this->expect( $this->user->get_lastname() ) ->to() ->equal( "Simpson" );

        });

    });

});
```

It is also possible to define named expressions at a global level in the `Specs_Runner::config`, but keep in mind that that will make each spec less expressive and will make it more difficult to understand:

```php
\Haijin\Specs\Specs_Runner::configure( function($specs) {

    $this->let( "user_id", function() {
        return 1;
    });

});
```
<a name="c-2-6"></a>
### Defining methods with def(...)

Define methods using the `def($method_name, $closure)` statement.

The behaviour and scope of the methods is very much the same as for `let(...)` expressions.

Example:

```php
$spec->describe( "...", function() {

    $this->def( "sum", function($n, $m) {
        return $n + $m;
    });

    $this->it( "...", function(){

        $this->expect( $this->sum( 3, 4 ) ->to() ->equal( 7 );

    });

});
```

<a name="c-2-7"></a>
### Defining custom expectations

<a name="c-2-7-1"></a>
#### Expectation definition structure

Expectation definitions have 4 parts, each defined with a closure.

The first one is the `$this->before($closure)` closure. This closure is evaluated before evaluating an expectation on a value. This block is optional but it can be used to perform complex calculations needed by the expectations for both the assertive and the negated closures.

The second one is the `$this->assert_with($closure)` closure. This closure is evaluated to evaluate a possitive expectation on a value.

The third one is the `$this->negate_with($closure)` closure. This closure is evaluated to evaluate a negated expectation on a value.

The fourth one is the `$this->after($closure)` closure. This closure is evaluated after the expectation is run, even when an Expectation_Failure was raised. This closure is optional but it can be used to release resources allocated during the evaluation of the previous closures.

<a name="c-2-7-2"></a>
#### Getting the value being validated

To get the actual value being validated, use `$this->actual_value`.

<a name="c-2-7-3"></a>
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

<a name="c-2-7-4"></a>
#### Raising expectation errors

To raise an expectation failure use `$this->raise_failure( $failure_message )`.

<a name="c-2-7-5"></a>
#### Evaluating closures within custom expectations

To evaluate closures within a custom expectation definition use `evaluate_closure($closure, ...$params)`.

This is required for the closure to evaluate with the propper binding.

Example:

```php
Value_Expectations::define_expectation( "custom_expectation", function() {

    $this->assert_with( function($expected_closure) {

        $this->evaluate_closure( $expected_closure, $this->actual_value );

        // ...
    });
);
```

<a name="c-2-7-6"></a>
#### Complete example

Here is a complete example of a custom validation:

```php

Value_Expectations::define_expectation( "equal", function() {

    $this->before( function($expected_value) {
        $this->got_expected_value = $expected_value == $this->actual_value;
    });

    $this->assert_with( function($expected_value) {

        if( $this->got_expected_value ) {
            return;
        }

        $this->raise_failure(
            "Expected value to equal {$expected_value}, got {$this->actual_value}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to equal {$expected_value}, got {$this->actual_value}."
        );
    });

    $this->after( function($expected_value) {
    });
});
```

<a name="c-2-8"></a>
#### Temporary skipping a spec

To temporary skip a spec or a group of specs prepend an `x` to its definition:

```php
$spec->describe( "When searching for users", function() {

    $this->let( "user_id", function() {
        return 1;
    });

    $this->xit( "finds the user by id", function(){

        $user = Users::find_by_id( $this->user_id );

        $this->expect( $user ) ->not() ->to() ->be_null();

    });

    $this->xdescribe( "the retrieved user data", function() {

        $this->let( "user", function() {
            return Users::find_by_id( $this->user_id );
        });

        $this->it( "includes the name", function() {

            $this->expect( $this->user->get_name() ) ->to() ->equal( "Lisa" );

        });

        $this->it( "includes the lastname", function() {

            $this->expect( $this->user->get_lastname() ) ->to() ->equal( "Simpson" );

        });

    });

});
```
<a name="c-2-9"></a>
#### Running the specs from the command line

```
php ./vendor/bin/specs
```

or add to the `composer.json` of the project the line:

```json
"scripts": {
    "specs": "php ./vendor/bin/specs"
}
```

and then run the specs with

```
composer specs
```

or a single spec file with

```
composer specs tests/specs/variables-scope/variables-scope.php
```

<a name="c-3"></a>
## Running this project tests

```
composer specs
```