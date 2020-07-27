<?php


namespace Drupal\social_auth_37signals\Controller;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\social_auth\Controller\OAuth2ControllerBase;
use Drupal\social_auth\SocialAuthDataHandler;
use Drupal\social_auth\User\UserAuthenticator;
use Drupal\social_auth_37signals\ThirtySevenSignalsAuthManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ThirtySevenSignalsAuthController extends OAuth2ControllerBase
{
  /**
   * ThirtySevenSignalsAuthController constructor.
   * @param MessengerInterface $messenger
   * @param NetworkManager $network_manager
   * @param UserAuthenticator $user_authenticator
   * @param ThirtySevenSignalsAuthManager $thirty_seven_manager
   * @param RequestStack $request
   * @param SocialAuthDataHandler $data_handler
   * @param RendererInterface $renderer
   */
  public function __construct(MessengerInterface $messenger,
                              NetworkManager $network_manager,
                              UserAuthenticator $user_authenticator,
                              ThirtySevenSignalsAuthManager $thirty_seven_manager,
                              RequestStack $request,
                              SocialAuthDataHandler $data_handler,
                              RendererInterface $renderer) {

    parent::__construct(
      'Social Auth 37Signals', 'social_auth_37signals',
      $messenger, $network_manager, $user_authenticator,
      $thirty_seven_manager, $request, $data_handler, $renderer);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('plugin.network.manager'),
      $container->get('social_auth.user_authenticator'),
      $container->get('social_auth_37signals.manager'),
      $container->get('request_stack'),
      $container->get('social_auth.data_handler'),
      $container->get('renderer')
    );
  }

  /**
   * Callback function to login user.
   *
   * Most of the providers' API redirects to a callback url. Most work for the
   * callback of an OAuth2 protocol is implemented in processCallback. However,
   * you should adapt this method to the provider's requirements.
   *
   * This method is called in 'social_auth_37signals.callback' route.
   *
   * @see social_auth_37signals.routing.yml
   * @see \Drupal\social_auth\Controller\OAuth2ControllerBase::processCallback
   *
   * This method is triggered when the path user/login/example/callback is
   * requested. It calls processCallback which creates an instance of
   * the Network Plugin 'social auth example'. It later authenticates the user
   * and creates the service to obtain data about the user.
   */
  public function callback() {

    $request_query = $this->request->getCurrentRequest()->query;

    // Checks if authentication failed.
    if ($request_query->has('error')) {
      $this->messenger->addError($this->t('You could not be authenticated.'));

      $response = $this->userAuthenticator->dispatchAuthenticationError($request_query->get('error'));
      if ($response) {
        return $response;
      }

      return $this->redirect('user.login');
    }

    /* @var \League\OAuth2\Client\Provider\GoogleUser|null $profile */
    $profile = $this->processCallback();

    // If authentication was successful.
    if ($profile !== NULL) {

      // Gets (or not) extra initial data.
      $data = $this->userAuthenticator->checkProviderIsAssociated($profile->getId()) ? NULL : $this->providerManager->getExtraDetails();

      return $this->userAuthenticator->authenticateUser(
        $profile->getName(),
        $profile->getEmail(),
        $profile->getId(),
        $this->providerManager->getAccessToken(),
        // $profile->getAvatar(),
        NULL,
        $data);
    }

    return $this->redirect('user.login');
  }
}
