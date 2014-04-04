error-reporting
===============

Usage:

Add this to your index.php file, right after autoloading.
```php
$errorService = new \BitWeb\ErrorReporting\Service\ErrorService(array(
    'subject' => '[Errors][your-app-id-here]',
    'emails' => array (
        'you@domain.com'
    ),
    'from_address' => 'you@domian.com',
    'ignore404' => false,
    'ignoreBot404' => false,
    'botList' => array(
        'AhrefsBot',
        'bingbot',
        'Ezooms',
        'Googlebot',
        'Mail.RU_Bot',
        'YandexBot',
    ),
));
$errorService->startErrorHandling();
```
Add this to the last line of your index.php
```php
$errorService->endErrorHandling();
```
