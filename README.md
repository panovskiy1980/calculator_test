Calculator class.
________________________________________________________________________________

Requirements.

PHP 7.1.
________________________________________________________________________________

API documentation.

apidocs folder.
________________________________________________________________________________

How to use from inside an application (example): 

index.php.
________________________________________________________________________________

To use from console (example):

    'php bin {Operation} {Number}'.
________________________________________________________________________________

Possible operations:

    add {Number}, subtract {Number}, divide {Number}, multiply {Number}, apply.
________________________________________________________________________________

What could be done next:
    Calculator class can be extended;
    Calculator\Classes\SimpleFileCache can be changed on your own;
    Config.php file can be also loaded from outside, i.e. changed completely
    (it needs to extend Calculator class and override related method)
________________________________________________________________________________

Testing.

Windows: .\vendors\bin\phpunit (or phpunit.bat)
Linux\Unix: ./vendor/bin/phpunit

P.S.: if nothing of above works (tests), you should try your own logic 
(i.e. was tested on Windows only).
