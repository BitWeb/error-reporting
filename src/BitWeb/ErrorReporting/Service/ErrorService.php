<?php
/**
 * Created by PhpStorm.
 * User: priit
 * Date: 3/5/14
 * Time: 10:05 PM
 */
namespace BitWeb\ErrorReporting\Service;

use BitWeb\ErrorReporting\Configuration;
use BitWeb\ErrorReporting\Error;
use BitWeb\ErrorReporting\ErrorEventManager;
use BitWeb\ErrorReporting\ErrorInfo;
use BitWeb\ErrorReporting\ErrorMeta;
use BitWeb\Stdlib\Ip;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

class ErrorService
{

    /**
     * @var Configuration
     */
    protected $configuration = null;

    /**
     * @var ErrorEventManager
     */
    protected $eventManager;

    protected $event;

    /**
     * @param \BitWeb\ErrorReporting\ErrorEventManager $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }


    public $errors = [];
    protected $startTime = null;

    protected static $errorException;

    public function __construct(Configuration $configuration)
    {
        $this->setConfig($configuration);
    }

    public function setConfig(Configuration $configuration)
    {
        $this->configuration = $configuration;
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

        if ($this->isIgnorablePath()) { //Ignore user defined paths
            return;
        }

        $this->composeAndSendErrorMail();
        $this->startTime = null;
        $this->errors = [];
    }

    public function hasReceiverEmails()
    {
        if (count($this->configuration->getEmails()) == 0) {
            return false;
        }
        return true;
    }

    public function ignoreBot404()
    {
        return $this->configuration->getIgnoreBot404();
    }

    public function ignore404()
    {
        return $this->configuration->getIgnore404();
    }

    public function isBotRequest()
    {
        $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $botList = $this->configuration->getBotList();

        foreach ($botList as $bot) {
            if (stripos($httpUserAgent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function hasOnlyIgnorableExceptions()
    {
        if ($this->configuration->getIgnorableExceptions() == null) {
            return false;
        }
        $ignorableExceptions = $this->configuration->getIgnorableExceptions();
        foreach ($this->errors as $error) {
            foreach ($ignorableExceptions as $ignorable) {
                if (!($error instanceof $ignorable)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function isIgnorablePath()
    {
        if ($this->configuration->getIgnorablePaths() == null || !isset($_SERVER['REQUEST_URI'])) {
            return false;
        }
        $ignorablePaths = array_map(function($path) {
            return '(' . str_replace('/', '\/', $path) . ')';
        }, $this->configuration->getIgnorablePaths());

        $pattern = '/(' . implode('|', $ignorablePaths) . ')+/i';
        $path = $_SERVER['REQUEST_URI'];

        return (boolean) preg_match($pattern, $path);
    }

    public function restoreDefaultErrorHandling()
    {
        return restore_error_handler();
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

        $to = array();
        $cc = array();

        foreach($this->configuration->getEmails() as $index => $mail){
            if($index == 0){
                $to = array('email' => $mail, 'name' => '');
            } else {
                $cc[] = array('email' => $mail, 'name' => '');
            }
        }

        $params = array(
            'to' => $to,
            'cc' => $cc,
            'from' => array(
                'name' => '',
                'email' => $this->configuration->getFromAddress(),
            ),
            'subject' => $this->configuration->getSubject(),
            'body' => $renderedView,
        );

        $this->eventManager->trigger($this->event, null, $params);
    }
}
