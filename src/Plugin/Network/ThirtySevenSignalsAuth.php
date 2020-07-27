<?php


namespace Drupal\social_auth_37signals\Plugin\Network;


use Drupal\Core\Url;
use Drupal\social_api\SocialApiException;
use Drupal\social_auth_37signals\Settings\ThirtySevenSignalsAuthSettings;
use Nilesuan\OAuth2\Client\Provider\Thirtysevensignals as ThirtySevenSignals;

/**
 * Defines Social Auth 37Signals Network Plugin.
 *
 * @Network(
 *   id = "social_auth_37signals",
 *   social_network = "37Signals",
 *   type = "social_auth",
 *   handlers = {
 *     "settings": {
 *       "class": "\Drupal\social_auth_37signals\Settings\ThirtySevenSignalsAuthSettings",
 *       "config_id": "social_auth_37signals.settings"
 *     }
 *   }
 * )
 */
class ThirtySevenSignalsAuth extends \Drupal\social_api\Plugin\NetworkBase
{

    /**
     * @inheritDoc
     */
    protected function initSdk()
    {
      $class_name = '\Nilesuan\OAuth2\Client\Provider\Thirtysevensignals';
      if (!class_exists($class_name)) {
        throw new SocialApiException(sprintf('The ThirtySevenSignals library for PHP League OAuth2 not found. Class: %s.', $class_name));
      }

      /** @var ThirtySevenSignalsAuthSettings $settings */
      $settings = $this->settings;

      if ($this->validateConfig($settings)) {
        // All these settings are mandatory.
        $league_settings = [
          'clientId' => $settings->getClientId(),
          'clientSecret' => $settings->getClientSecret(),
          'redirectUri' => Url::fromRoute('social_auth_37signals.callback')->setAbsolute()->toString(),
        ];

        // Proxy configuration data for outward proxy.
        $proxyUrl = $this->siteSettings->get('http_client_config')['proxy']['http'];
        if ($proxyUrl) {
          $league_settings['proxy'] = $proxyUrl;
        }

        return new ThirtySevenSignals($league_settings);
      }

      return FALSE;
    }

  /**
   * Checks that module is configured.
   *
   * @param ThirtySevenSignalsAuthSettings $settings
   *   The implementer authentication settings.
   *
   * @return bool
   *   True if module is configured.
   *   False otherwise.
   */
  protected function validateConfig(ThirtySevenSignalsAuthSettings $settings) {
    $client_id = $settings->getClientId();
    $client_secret = $settings->getClientSecret();
    if (!$client_id || !$client_secret) {
      $this->loggerFactory
        ->get('social_auth_37signals')
        ->error('Define Client ID and Client Secret on module settings.');

      return FALSE;
    }

    return TRUE;
  }

}
