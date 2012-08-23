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
* Requiring the whole data setin memory at once
* Processing is called on every value, even if not every value is needed
* Usage of the processed values happens away from processing

Using a "deferred processing pipe", the values aren't transformed until they are needed, and even then, they are transformed on demand:

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
