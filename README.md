\pcov\Clobber
=============

PCOV support was merged into PHPUnit 8, and I __strongly__ encourage you to upgrade to PHPUnit 8 ... however ...

Since for some people, this is turning out to be a nightmare because of the usage of void, and type system rules; Here is a package that will clobber the Xdebug driver in versions of PHPUnit before 8, it does this by using uopz to trick php-code-coverage into detecting Xdebug is loaded, and replacing the definition of the Xdebug driver with a compatible PCOV driver.

Therefore this package relies on uopz 6.0+, the loading of uopz has a small detrimental effect on the performance of code, however, your coverage jobs will still run very much quicker than with Xdebug, and using less memory.

Usage
=====

Suggested installation steps:

```
composer require --dev pcov/clobber
composer config scripts.post-autoload-dump "\\pcov\\Clobber::autoload"
composer dump-autoload
```

NOTE: Installing composer dependencies with `--no-dev` flag (ie: production) will render a warning which can be safely ignored:
```
Class \pcov\Clobber is not autoloadable, can not call post-autoload-dump script
```
