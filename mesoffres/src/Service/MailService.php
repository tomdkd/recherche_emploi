<?php

namespace Drupal\mesoffres\Service;

class MailService {

  public function getConfig() {
    $config = \Drupal::configFactory()->getEditable('mesoffres.settings');
    return $config;
  }

  public function verifyConfig($param) {
    $config = $this->getConfig();
    if ($config->get('notification')) {
      return TRUE;
    }
  }

  public function sendMail($subject, $message, $options = NULL) {
    $config = $this->getConfig();

    $module = 'mesoffres';
    $key = 'notification';
    $to = $config->get('mail');

    $params['message'] = $message;
    $params['subject'] = $subject;
    $params['options']['username'] = \Drupal::currentUser()->getAccountName();
  }

}
