error-reporting
===============
[![Build Status](https://travis-ci.org/BitWeb/error-reporting.svg?branch=master)](https://travis-ci.org/BitWeb/error-reporting?branch=master)
[![Coverage Status](https://img.shields.io/coveralls/BitWeb/error-reporting.svg)](https://coveralls.io/r/BitWeb/error-reporting?branch=master)

###PHP5.4 compatibility branch

##Usage:

Add this to your index.php file, right after autoloading.
```php
$errorService = new \BitWeb\ErrorReporting\Service\ErrorService(array(
    'errorReportingLevel' => E_ALL,
    'subject' => '[Errors][your-app-id-here]',
    'emails' => array (
        'you@domain.com'
    ),
    'from_address' => 'you@domain.com',
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
    'ignorableExceptions' => array(
        'ErrorException'
    ),
    'ignorablePaths' => array (
        'paths/to/ignore'
    )
));
$errorService->startErrorHandling();
```
Add this to the last line of your index.php
```php
$errorService->endErrorHandling();
```
## Configuration

| Name                 | Type    | Default                   | Description |
|----------------------|---------|---------------------------|-------------|
| errorReportingLevel  | integer | E_ALL                     | The level of error reporting. |
| subject              | string  | `Errors`                  | The subject of the message being sent. |
| emails               | array   | `array()`                 | An array of emails the error report is sent to. |
| from_address         | string  | `''`                      | Address where the message is sent from. |
| ignore404            | boolean | `false`                   | Are 404 errors ignored? |
| ignoreBot404         | boolean | `false`                   | Are bot 404 errors ignored? |
| botList              | array   | `array()`                 | Defines bots. |
| ignorableExceptions  | array   | `array('ErrorException')` | Exceptions to ignore. |
| ignorablePaths       | array   | `array()`                 | Paths to ignore. |
