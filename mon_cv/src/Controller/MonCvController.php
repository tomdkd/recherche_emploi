<?php

namespace Drupal\mon_cv\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

class MonCvController extends ControllerBase {

  public function display() {

    $markup = "Coucou";

    $table[] = [
      '#markup' => $markup,
    ];

    return $table;

  }

  public function getConfigFile() {
    return 'form_file';
  }

}
