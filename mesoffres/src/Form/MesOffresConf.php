<?php

namespace Drupal\mesoffres\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Zend\Diactoros\Response\RedirectResponse;

class MesOffresConf extends ConfigFormBase {

  public function getFormId(){
    return 'mes_offres_configuration';
  }

  protected function getEditableConfigNames(){
    return 'mesoffres.settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $form['notification'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Activer la notification par mail'),
      '#default_value' => $this->getsetConfig('get', 'notification'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    $this->getsetConfig('set', 'notification', $form_state->getValue('notification'));
    $this->getsetConfig('set', 'mail', $form_state->getValue('mail'));

    $message = $this->t('La configuration a été enregistrée avec succés');
    $this->messenger()->addMessage($message);
  }

  protected function getsetConfig($method, $key, $value = NULL) {
    $configname = $this->getEditableConfigNames();
    $config = \Drupal::configFactory()->getEditable($configname);

    if ($method == 'set') {
      $config->set($key, $value);
      $config->save();
      return;
    }
    elseif ($method == 'get') {
      $data = $config->get($key);
      return $data;
    }
    else {
      $message = $this->t('Erreur dans la méthode getsetConfig(). La méthode spécifiée doit être "get" ou "set"');
      \Drupal::logger('mesoffres')->error($message);
      return $this->messenger()->addError($this->t('Il y a eu une erreur'));
    }
  }

}
