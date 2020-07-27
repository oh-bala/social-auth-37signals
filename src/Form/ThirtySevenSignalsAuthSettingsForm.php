<?php


namespace Drupal\social_auth_37signals\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ThirtySevenSignalsAuthSettingsForm extends \Drupal\social_auth\Form\SocialAuthSettingsForm
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array_merge(['social_auth_37signals.settings'], parent::getEditableConfigNames());
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_auth_37signals_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_auth_37signals.settings');

    $form['37signals_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('37Signals Client settings'),
      '#open' => TRUE,
      '#description' => $this->t('You need to first create a Basecamp App at <a href="@basecamp-launchpad">@basecamp-launchpad</a>',
        ['@basecamp-launchpad' => 'https://launchpad.37signals.com/integrations']),
    ];

    $form['37signals_settings']['client_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Client ID here'),
    ];

    $form['37signals_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Client Secret here'),
    ];

    $form['37signals_settings']['authorized_redirect_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Authorized redirect URIs'),
      '#description' => $this->t('Copy this value to <em>Authorized redirect URIs</em> field of your Basecamp App settings.'),
      '#default_value' => Url::fromRoute('social_auth_37signals.callback')->setAbsolute()->toString(),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->config('social_auth_37signals.settings')
      ->set('client_id', $values['client_id'])
      ->set('client_secret', $values['client_secret'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
