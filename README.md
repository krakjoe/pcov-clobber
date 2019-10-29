\pcov\Clobber
=============

PCOV support was merged into PHPUnit 8, and I __strongly__ encourage you to upgrade to PHPUnit 8 ... however ...

Since for some people, this is turning out to be a nightmare because of the usage of void, and type system rules; Here is a package that will clobber the Xdebug driver in version 5, 6 and 7 of PHPUnit.

Usage
=====

`composer require pcov/clobber` in your project, this will install the drivers in your vendor directory and in addition, it will install `bin/pcov`.

To clobber the Xdebug driver in the current directory:

`vendor/bin/pcov clobber`

To unclobber the Xdebug driver (revert changes):

`vendor/bin/pcov unclobber`

To clobber the Xdebug driver in another directory:

`vendor/bin/pcov /path/to/source clobber`

To unclobber the Xdebug driver in another directory:

`vendor/bin/pcov /path/to/source unclobber`

*Note: `pcov/clobber` needs to be a dependency in any directory where `bin/pcov` operates.*
