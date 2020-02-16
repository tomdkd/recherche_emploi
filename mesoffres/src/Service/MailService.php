<?php

namespace Drupal\mesoffres\Service;

class MailService {

  public function getConfig() {
    $config = \Drupal::configFactory()->getEditable('mesoffres.settings');
    return $config;
  }

  public function sendMail($offres) {
    $message = 'Un délai de 7 jours ou plus s\'est écoulé. Vous pouvez désormais relancer ces entreprises : ' . $offres;
    $mailmanager = \Drupal::service('plugin.manager.mail');
    $langcode = \Drupal::config('system.site')->get('langcode');
    $module = 'mesoffres';
    $key = 'mail_notification';
    $to = \Drupal::currentUser()->getEmail();
    $reply = NULL;
    $send = TRUE;

    $params['message'] = t($message);
    $params['subject'] = t('Votre récapitulatif');
    $params['options']['username'] = \Drupal::currentUser()->getAccountName();

    $results = $mailmanager->mail($module, $key, $to, $langcode, $params, $reply, $send);

    if ($results) {
      $message = "Un mail récapitulatif a été envoyé";
      return \Drupal::messenger()->addMessage(t($message));
    }
    else {
      $message = "Impossible d'envoyer le mail récapitulatif";
      return \Drupal::messenger()->addError(t($message));
    }
  }

}
