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
* Generate every single field/property and data type per field reflect Laravel Validation.
* For multiple validation like `"platformCode" => "string|max:20"`, first data type must be Max. Then command line will ask you value of max(in this case is 20). Then it will ask you type of value as `string` in this case.
![Demo](https://gyazo.com/64e34fa744b95cc01d29a546161ed69c)