<?php

namespace Drupal\social_auth_37signals\Settings;

/**
 * Defines an interface for Social Auth 37Signals settings.
 */
interface ThirtySevenSignalsAuthSettingsInterface
{
  /**
   * Gets the client ID.
   *
   * @return string
   *   The client ID.
   */
  public function getClientId();

  /**
   * Gets the client secret.
   *
   * @return string
   *   The client secret.
   */
  public function getClientSecret();
}
