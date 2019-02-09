# Find Unreachable Code (phpfu)
phpfu detects code that did not pass from opcache

# Installation

```
composer require --dev nakamura244/phpfu
```

# Usage
```php
<?php

echo a();

function a()
{
    try {
        $a = 42;
        return $a;
    } finally {
        return 42;
    }
}
```

```
$ php fu example.php                 
phpfu 0.1.0 by nakamura244.

Unuse code?: 

method: a: [path]/example.php:5-13
bytecode: 
BB3: unreachable lines=[4-4]
BB4: unreachable lines=[5-5]
BB7: unreachable lines=[9-9]
```

# Caution
