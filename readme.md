# Data generator

Wicked awesome data generator for FuelPHP.

The package comprises a couple of classes and a frontend to them. Simply drop the repo into
your fuel/app/modules directory, and then put the JS into public/assets/datagenerator/js.

You can also put the JS file directly in public/assets/js since Fuel looks there by default.

# Config

You will need to create a DB connection called 'datagenerator' because it uses its own
database tables to get some of its data.

You will also need to configure datagenerator.dict to point to a dictionary file if you want
to use the "{word}" format for string data, since this picks random strings from a word list.

# Usage

Either:

a) use the web interface at http://yourapp/datagenerator/home/index

or

b) create an array of \Datagenerator\FieldTemplate objects and pass it to 
\Datagenerator\DataGenerator::generate to get an array of data.

## FieldTemplates

You create a FieldTemplate with a type

    $f = new FieldTemplate('type')

and then you set the value, which will be a string describing the format of data relevant
to that type.

    $v->value('format string');

An array of these passed to \Datagenerator\DataGenerator::generate will produce an array of
associative arrays of data.

## DataGenerator::generate

This function takes a) the above-mentioned array of objects and b) a quantity to generate.

# Types and formats

There are three main types and a few secondary types. The titles here will be the value you pass
to the constructor of your FieldTemplate, and the value you set on that object will comprise one
or more of the format strings available.

Creating that value is simply a case of writing a string containing `{these}`. Each of those will
be taken out and replaced with random data, based on what is inside the brackets. The rest of the
string will be left static.

You can also specify parameters for them by using colons, e.g. `{type:param1:param2}`.

Check out the static array in FieldTemplate for some examples.

## string

### initial

`{initial}` will be replaced with a single random capital letter. There are no parameters
for this.

### lipsum:concat:what:min:max

`{lipsum}` will create a section of lorem ipsum text. Lipsum uses lipsum.org and hence requires a net
connection. Feel free to patch this.

The 'concat' parameter defaults to a single space. The string '\n' will be understood as a newline.

The 'what' parameter determines what to create and defaults to 'words'. Options are 'bytes', 
'words', 'paras' and 'lists'. I don't really know what 'lists' does.

The 'min' and 'max' parameters determine a range for the random number of 'what's to generate. Min
defaults to 1 and max defaults to whatever min was set to.

`{lipsum}` is therefore short for `{lipsum: :words:1:1}`

### domain

`{domain}` will be replaced by any valid domain, simply by taking a lipsum word or two and putting dots
between them.

TODO: This format needs to take a parameter for the min and max number of parts to the domain.

### word

`{word}` is replaced with any word from the file you configured in the datagenerator.dict config setting.

### rand:min:max:pattern

`{rand}` creates a random string.

The 'min' and 'max' parameters define a range for the random number of characters in the string. Min
defaults to 8 and max defaults to whatever min was set to.

The 'pattern' parameter determines characters that may be used. Character-class-style ranges are understood,
and the default character set is `'a-zA-Z0-9'`

`{rand}` is therefore short for `{rand:8:8:a-zA-Z0-9}`

### surname, forename

`{surname}` and `{forename}` are replaced with random values taken from the `string_template_values` table in 
the database.

### tld

`{tld}` is replaced with a random value from the `string_template_values` table in the database. TLDs in there
do not have the leading dot, but any interim dots (e.g. co.uk) are there.

## date

TODO: Currently the value for date does not use the `{}` syntax to delimit its formats.

The value provided to the date type is passed directly to the `strftime` function (no feedback about the `date`
function, please; `strftime` is a POSIX standard and probably older than you are). It is given a random date
between now and epoch.

TODO: Accept a date range.

## enum

TODO: The enum type does not delimit its parts with `{}`.

This simply divides the entire string on the `|` character and selects one of the resulting array.

TODO: Allow multiple for SET fields.

## number

`{num}`
`{min:max}`

Number fields replace any part matching `/\{\d+\}/` with that number of digits. E.g. `{10}` is replaced with 10
random digits.

The pattern `/\{\d+:\d+}/` is a min:max situation and will generate a number between min and max digits long. So
`{4:10}` is replaced with between 4 and 10 digits.

To do this inside a string field you can use `{rand:4:10:0-9}` (or `{rand:10:10:0-9}`) instead.

## maybe-formats

`{?rand}`
`{?=4rand}`

A maybe-format is the `{}` format code with a `?` immediately following the
opening brace. This can be used for the number and string field types.

To use it, simply put a `?`, as noted, after the opening brace:

    {?1:9}

This will be given a value 50% of the time, and will be replaced with the
empty string at other times.

You can control how often it will appear by using the extended syntax:

    {?=12:1:9}

The whole structure `?=12:` is the maybe frequency; in this case the field
will be displayed one in 12 times. Remember to use the colon to separate the
frequency number from the actual format code.

`2` is your current minimum because I got lazy with regexes and didn't fancy
implementing floats.

# Bugs and TODOs

Well it's not perfect so it's probably buggy.

But mostly the TODOs regarding field formats above, plus I need to refactor the FieldTemplate class a bit because
it doesn't really need to be a class at all; it's just helper functions. The API to DataGenerator::generate could
simply take an array to the same effect.
