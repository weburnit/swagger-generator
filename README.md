#Install
```php

namespace YourNamespace\Console;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Weburnit\Console\Commands\SwaggerModelGenerator;
/**
 * Class Kernel
 */
class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SwaggerModelGenerator::class,
        ...
    ];
}
```
#Usage
* Be able to create Class, Class Description
* Generate every single field/property and data type per field reflects Laravel Validation.
* In case field has multiple validations like `"platformCode" => "string|max:20"`, first data type must be `max`. Then command line will ask you value of max(in this case is 20). Then it will ask you type of value as `string` in this case.
[![Demo](https://i.gyazo.com/64e34fa744b95cc01d29a546161ed69c.gif)](https://gyazo.com/64e34fa744b95cc01d29a546161ed69c)
