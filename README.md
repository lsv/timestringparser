Time string parser &#9679; [![Build Status](https://travis-ci.org/lsv/timestringparser.svg?branch=master)](https://travis-ci.org/lsv/timestringparser) [![Coverage Status](https://coveralls.io/repos/github/lsv/timestringparser/badge.svg?branch=master)](https://coveralls.io/github/lsv/timestringparser?branch=master)
===========================================================================================================================================================================================================================================================================================================================

If you have strings like 1h30m and want this to be a integer with minutes, this is for you.

The hour letters and minute letters can be customized and also multiple is allowed.

* 1h20m = 80
* 3:24 = 204
* 954 = 954

And if you have multiple hour letters you can set these in the constructor, see under [usage](#Usage), so you can end up with parsing multiple letters
 
* 2t20 = 140
* 3x84s = 264

##### New in 2.0

* You can now add a low minute, meaning if set it to 4, then if you write 3, it will become 3 hours, if you write 5 it will still be 5 minutes.
* By default the `low minute` variable is NULL, meaning its not used

### Install

`composer require lsv/timestringparser`

or add

```json
{
    "require": {
        "lsv/timestringparser": "^1.0"
    }
}
```

to your `composer.json`

### Usage

By standard hour letter is `h` and minute letter is `m` but these can be customized in the constructor `new TimestringParser(['h','t','u'], ['m','x','y']);` now `h`, `t` and `u` can be used to parse the hour part of the time string, and the letters `m`, `x`, `y` can be used to parse the minute part of the time string
 
##### New in 2.0

You can set the `low minute` variable in the constructor `new TimestringParser(['h','t','u'], ['m','x','y'], <low minute>);`

It needs to be a integer
 
### Examples

```php
$timestrings = [
    '3:20', '1h 42m', '1u 22x', '3u55x',
    20, '1h', '48h84y', '3t994x'
];
$parser = new TimestringParser(['h','t','u'], ['m','x','y']);
foreach($timestrings as $string) {
    echo $parser->parseTimeString($string); // Return integers
}
```

##### Low minute example

```
$timestrings = [1, 2, '3', '4', 5];
$parser = new TimestringParser(['h'], ['m'], 3);
echo $parser->parseTimeString(1); // Returns 60
echo $parser->parseTimeString(2); // Returns 120
echo $parser->parseTimeString('3'); // Returns 180
echo $parser->parseTimeString('4'); // Returns 4
echo $parser->parseTimeString(5); // Returns 5
```

### License

The MIT License (MIT)

Copyright (c) 2016 Martin Aarhof <martin.aarhof@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
