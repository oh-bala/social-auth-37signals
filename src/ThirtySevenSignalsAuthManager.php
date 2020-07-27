<?php


namespace Drupal\social_auth_37signals;


use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\social_auth\AuthManager\OAuth2Manager;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\RequestStack;

class ThirtySevenSignalsAuthManager extends OAuth2Manager
{
  /**
   * The session manager.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  protected $session;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * ThirtySevenSignalsAuthManager constructor.
   * @param ConfigFactory $config_factory
   * @param LoggerChannelFactoryInterface $logger_factory
   * @param RequestStack|null $request_stack
   */
  public function __construct(ConfigFactory $config_factory, LoggerChannelFactoryInterface $logger_factory, RequestStack $request_stack = NULL)
  {
    parent::__construct($config_factory->get('social_auth_37signals.settings'), $logger_factory, $request_stack->getCurrentRequest());
  }

  /**
   * @inheritDoc
   */
  public function authenticate()
  {
    try {
      $this->setAccessToken($this->client->getAccessToken('authorization_code', ['code' => $this->request->get('code'), 'type' => 'web_server']));
    }
    catch (\Exception $e) {
      $this->loggerFactory->get('social_auth_37signals')->error('There was an error during authentication. Exception: ' . $e->getMessage());
    }
  }

  /**
   * @inheritDoc
   */
  public function requestEndPoint($method, $path, $domain = NULL, array $options = [])
  {
    if (!$domain) {
      $domain = 'https://launchpad.37signals.com';
    }

    $url = $domain . trim($path);

    $request = $this->client->getAuthenticatedRequest($method, $url, $this->getAccessToken(), $options);

    try {
      return $this->client->getParsedResponse($request);
    }
    catch (IdentityProviderException $e) {
      $this->loggerFactory->get('social_auth_37signals')
        ->error('There was an error when requesting ' . $url . '. Exception: ' . $e->getMessage());
    }

    return NULL;
  }

  /**
   * @inheritDoc
   */
  public function getAuthorizationUrl()
  {
    return $this->client->getAuthorizationUrl();
  }

  /**
   * @inheritDoc
   */
  public function getState()
  {
    return $this->client->getState();
  }

  /**
   * @inheritDoc
   */
  public function getUserInfo()
  {
    if (!$this->user) {
      $this->user = $this->client->getResourceOwner($this->getAccessToken());
    }

    return $this->user;
  }
}
