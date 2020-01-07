<?php

namespace Drupal\mesoffres\Controller;

use Drupal\Core\Controller\ControllerBase;

class MesOffresController extends ControllerBase {

  public function list() {
    $table[] = [
      '#markup' => "Bonjour",
    ];

    return $table;
  }

}
