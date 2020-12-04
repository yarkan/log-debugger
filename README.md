# Yarkan / ldg

### 1. Configuration:

- *Environment:* Define the parameters
    - *Name:* name of file
    - *Route:* route of write the file
    - *Route Level:* level of directories to need to find a project dir 

~~~
LDG_NAME=Waring
LDG_ROUTE=/var/log
LDG_ROUTE_LEVEL=5
~~~

### 2. Usage

- *Line*: Single line, values: String|Numeric

~~~
LDG\Write::local()->line('Test', 12);
~~~

- *Json*: Convert to json structure, values: Object|Array 

~~~
LDG\Write::local()->json([12, 'Test'], new Test());
~~~

- *Implement*: Show class detailed, values: Object 

~~~
LDG\Write::local()->impl(new Test());
~~~

- *Error*: Show error message, values: \Throwable

~~~
LDG\Write::local()->error($exception);
~~~

- *Exception*: Show exception trace, values: \Throwable

~~~
LDG\Write::local()->exception($exception);
~~~

- *Print*: Show any structure more beautifully, values: String|Numeric|Array|Object

~~~
LDG\Write::local()->print('Test', 123, [123, 'Test'], new Test());
~~~

- *Dump*: Show any structure more detailed, values: String|Numeric|Array|Object

~~~
LDG\Write::local()->dump('Test', 123, [123, 'Test'], new Test());
~~~

### 3. Customized

- *Name*: customize a new name for the file

~~~
LDG\Write::local()->name('New name')->line('Test');
~~~

- *Memory*: expand memory to use

~~~
LDG\Write::local()->memory('128M')->line('Test');
~~~

- *Trace*: add back trace

~~~
LDG\Write::local()->trace();
LDG\Write::local()->showTrace()->line('Test');
~~~

- *Clear*: clear all file in the directory

~~~
LDG\Write::local()->clear()->line('Test');
~~~