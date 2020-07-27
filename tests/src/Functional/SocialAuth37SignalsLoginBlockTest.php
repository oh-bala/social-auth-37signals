<?php


namespace Drupal\Tests\social_auth_37signals\Functional;


class SocialAuth37SignalsLoginBlockTest extends \Drupal\Tests\social_auth\Functional\SocialAuthTestBase
{
  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['block', 'social_auth_37signals'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->provider = '37signals';
  }

  /**
   * Test that the path is included in the login block.
   *
   * @throws \Behat\Mink\Exception\ResponseTextException
   */
  public function testLinkExistsInBlock() {
    $this->checkLinkToProviderExists();
  }
}
