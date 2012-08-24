Plumber
=======
Library for easily creating processing chains.

Inspired and influenced by [TinkerPop Pipes](http://pipes.tinkerpop.com)

Author: Josh Adell <josh.adell@gmail.com>  
Copyright (c) 2011-2012

[![Build Status](https://secure.travis-ci.org/jadell/plumber.png?branch=master)](http://travis-ci.org/jadell/plumber)

Usage
-----

    $msg = array(72,0,101,108,108,111,44,32,0,87,111,114,108,0,100,33);

    $pipeline = new Everyman\Plumber\Pipeline();
    $pipeline->filter()->transform(function ($v) { return chr($v); });

    foreach ($pipeline($msg) as $c) {
      echo $c;
    }

Purpose
-------

Often times, it is necessary to loop through a list, filter out unneeded values, and perform one or more transformations on the remaining values. A good example of this is reading and formatting records from a database:

    $users = // code to retrieve user records from a database...

    $names = array();
    foreach ($users as $user) {
        if (!$user['first_name'] || !$user['last_name']) {
            continue;
        }

        $name = $user['first_name'] . ' ' . $user['last_name'];
        $name = ucwords($name);
        $name = htmlentities($name);
        $names[] = $name;
    }

    // later on, display the names
    foreach ($names as $name) {
        echo "$name<br>";
    }

There are several downsides to this process:
* Looping through the list more than once
* Requiring the whole data set in memory at once
* Process steps are not reusable

Using a "deferred processing pipe", the values aren't transformed until they are needed, on demand:

    $users = // code to retrieve user records from a database...

    $names = new Everyman\Plumber\Pipeline();
    $names->filter(function ($user) { return $user['first_name'] && $user['last_name']; })
        ->transform(function ($user) { return $user['first_name'] . ' ' . $user['last_name']; })
        ->transform('ucwords')
        ->transform('htmlentities');

    // later on, display the names
    foreach ($names($users) as $name) {
        echo "$name<br>";
    }

Built-in Pipes
--------------
_Plumber_ comes with several pre-built pipes that can be used immediately.

### Filter
_filter_ pipes remove values from the data to avoid further processing. Without providing a filter function, the filter pipe strips out values that cast to boolean `false`:

    $pipeline->filter();
    foreach ($pipeline(array(0, 1, false, true, '', 'abc', null, array(), array())) as $value) {
        echo $value.' ';
    }
    // Output: 1 1 abc Array

You can provide a filter function to use. The function should take 2 parameters, the value and key of the current element. If the function returns a truthy value, the element will continue to the next processing step:

    $pipeline->filter(function ($value, $key) {
        return $key % 2;
    });
    foreach ($pipeline(array(0, 1, 2, 9, 10, 67)) as $value) {
        echo $value.' ';
    }
    // Output: 0 2 10

Filter pipes are the basis of several other built-in pipes.

#### Unique
_unique_ pipes filter out any value that has previsouly been seen during processing:

    $pipeline->unique();
    foreach ($pipeline(array('foo', 'bar', 'baz', 'foo', 'baz', 'qux')) as $value) {
        echo $value.' ';
    }
    // Output: foo bar baz qux

#### Slice
_slice_ pipes return values after a given offset and up to a given length:

    $pipeline->slice(2,3);
    foreach ($pipeline(array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum')) as $value) {
        echo $value.' ';
    }
    // Output: baz quz lorem

If the second parameter is left off, all values after the offset are returned:

    $pipeline->slice(1);
    foreach ($pipeline(array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum')) as $value) {
        echo $value.' ';
    }
    // Output: bar baz quz lorem ipsum

#### Random
_random_ pipes emit values randomly based on a threshold. The threshold should be between 1 and 100, and represents the chance in 100 that a value will be emitted:

    $pipeline->random(40);
    foreach ($pipeline(array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum')) as $value) {
        echo $value.' ';
    }
    // Possible output: bar ipsum

### Transform
_transform_ pipes manipulate the incoming value and emit the output of the manipulation. Without providing a transform function, the pipe will emit every value as is:

    $pipeline->transform();
    foreach ($pipeline(array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum')) as $value) {
        echo $value.' ';
    }
    // Output: foo bar baz qux lorem ipsum

A transformation function should take 2 parameters, the current value and key in the pipeline:

    $pipeline->transform(function ($value, $key) {
        return strrev($value);
    });
    foreach ($pipeline(array('foo', 'bar', 'baz', 'qux', 'lorem', 'ipsum')) as $value) {
        echo $value.' ';
    }
    // Output: oof rab zab xuq merol muspi


