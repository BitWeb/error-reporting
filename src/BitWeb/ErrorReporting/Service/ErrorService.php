<?php
/**
 * Created by PhpStorm.
 * User: priit
 * Date: 3/5/14
 * Time: 10:05 PM
 */
namespace BitWeb\ErrorReporting\Service;

use BitWeb\ErrorReporting\Error;
use BitWeb\ErrorReporting\ErrorInfo;
use BitWeb\ErrorReporting\ErrorMeta;
use BitWeb\Stdlib\Ip;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

class ErrorService
{

    protected $config = [
        'emails' => [],
        'subject' => 'Errors',
        'from_address' => '',
        'botList' => [],
        'ignore404' => false,
        'ignoreBot404' => false,
        'ignorable_exceptions' => ['ErrorException'],
    ];

    public $errors = [];
    protected $startTime = null;

    protected static $errorException;

    public function __construct(array $config = [])
    {
        if (count($config) > 0) {
            $this->setConfig($config);
        }
    }

    public function setConfig(array $config = [])
    {
        $this->config = $config;
    }

    public function startErrorHandling($startTime = null)
    {
        $this->startTime = $startTime !== null ? $startTime : microtime(true);
        set_error_handler([$this, 'addPhpError'], E_ALL);
        register_shutdown_function([$this, 'endErrorHandlingWithFatal']);
    }

    public function addPhpError($errorLevel, $errorMessage, $errorFile, $errorLine)
    {
        $errorException = new \ErrorException($errorMessage, 0, $errorLevel, $errorFile, $errorLine, static::$errorException);
        $this->errors[] = $errorException;
    }

    public function endErrorHandlingWithFatal()
    {
        $error = error_get_last();
        if ($error['type'] & (E_ALL & ~E_STRICT) != 0) {
            $this->addPhpError($error['type'], $error['message'], $error['file'], $error['line']);
            $this->endErrorHandling();
        }
    }

    public function endErrorHandling()
    {
        if (empty($this->errors)) {
            return; //No errors, do nothing
        }
        if ($this->hasReceiverEmails() == null) { //If mail sending is disabled
            return;
        }

        if ($this->ignoreBot404() && $this->isBotRequest() && $this->hasOnlyIgnorableExceptions()) { //Do not send router-no-match errors with bot-requests to e-mails
            return;
        }

        if ($this->ignore404() && $this->hasOnlyIgnorableExceptions()) { //Ignore normal user 404 requests
            return;
        }

        $this->composeAndSendErrorMail();
        $this->startTime = null;
        $this->errors = [];
    }

    public function hasReceiverEmails()
    {
        if (!isset($this->config['emails'])) {
            return false;
        }
        if (count($this->config['emails']) == 0) {
            return false;
        }
        return true;
    }

    public function ignoreBot404()
    {
        if (isset($this->config['ignoreBot404']) && $this->config['ignoreBot404']) {
            return false;
        }
        return true;
    }

    public function ignore404()
    {
        if (isset($this->config['ignore404']) && $this->config['ignore404']) {
            return false;
        }
        return true;
    }

    public function isBotRequest()
    {
        $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $botList = $this->config['bot_list'];

        foreach ($botList as $bot) {
            if (stripos($httpUserAgent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function hasOnlyIgnorableExceptions()
    {
        if (!isset($this->config['ignorable_exceptions']) or count($this->config['ignorable_exceptions']) == 0) {
            return false;
        }
        $ignorableExceptions = $this->config['ignorable_exceptions'];
        foreach ($this->errors as $error) {

            foreach ($ignorableExceptions as $ignorable) {
                if (!($error instanceof $ignorable)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function restoreDefaultErrorHandling()
    {
        restore_error_handler();
    }

    public function getErrorReportMetaData()
    {
        $errors = [];
        /** @var $errorException \Exception */
        foreach ($this->errors as $errorException) {
            $errors[] = new ErrorInfo(
                get_class($errorException),
                $errorException->getMessage(),
                trim(ucfirst(nl2br($errorException->getTraceAsString()))),
                method_exists($errorException, 'getSeverity') ? $errorException->getSeverity() : null
            );
        }

        $meta = new ErrorMeta();
        $meta->setIp(Ip::getClientIp());
        $meta->setUserAgent((isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null);
        $meta->setUrl(((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
        $meta->setPostData($_POST);
        $meta->setGetData($_GET);
        $meta->setSessionData(isset($_SESSION) ? $_SESSION : null);
        $meta->setServerData($_SERVER);
        $meta->setReferrer((isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null);
        $meta->setRequestTime(new \DateTime());
        $meta->setRequestDuration(microtime(true) - $this->startTime);

        return new Error($errors, $meta);
    }

    protected function composeAndSendErrorMail()
    {
        $viewModel = new ViewModel([
            'error' => $this->getErrorReportMetaData()
        ]);
        $viewModel->setTemplate('mail/errors');
        $renderer = new PhpRenderer();
        $renderer->setResolver(new TemplateMapResolver([
            'mail/errors' => __DIR__ . '/../../../../view/mail/errors.phtml'
        ]));
        $renderedView = $renderer->render($viewModel);

        $to = implode(',', $this->config['emails']);

        $headers = "From: " . $this->config['from_address'] . "\n";
        $headers .= "MIME-Version: 1.0" . "\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\n";

        mail($to, $this->config['subject'], $renderedView, $headers);
    }
}
