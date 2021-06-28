# Leap
## Introduction

Leap is a PHP command-line application framework for Unix/Linux. We know there is a lot of CLI framework out there. We build this framework out of the need for the following:
1. Simplify the accessing of application parameters
2. Proivde a simple and scalable method of attaching input/output handlers
3. Turning the application into a daemon for Unix/Linux envoirnment
4. Make the "help" printout easy
5. Object-oriented your code

## Requirements

This library ues PHP 7.4 or higher.

## Installation

This package can be installed via composer
```
composer install leap
```

### Usage

Simple example of a cli app (helloworld.php)

```php
<?php

// path to where Leap.php is located
require_once("Leap.php");

class HelloWorld
    implements \leap\Leapable
{
    public function options() : array {
        // follow getopt() naming convention
        return [
            'h'   => [ $this, "print_hello" ],  // option that do not accept value
            'w::' => [ $this, "print_world" ],  // optional value
            'm:'  => [ $this, "print_my" ]      // requires value
        ];
    }

    public function main(\leap\Argument $arg = null) : int {
        $arg->invokeFunction( $this->options() );
        return 0;
    }
    
    public function print_hello($v) {
        echo "\nHello ";
    }
    
    public function print_world($v) {
        echo "World\n";
    }
    
    public function print_my($v) {
        echo $v . "\n";
    }
}

\leap\Leap::run(
    \leap\Leap::wrap(
        new \leap\cli\Wrapper(),
        new HelloWorld()
    )
);
```

Run the app

```
> php helloworld.php -h

Hello >
```

```
> php helloworld.php -h -w

Hello World
>
```

```
> php helloworld.php -h -w -m '!!'

Hello World
!!
>
```

For more example, you can run the script file, runtest.sh, in the test folder


