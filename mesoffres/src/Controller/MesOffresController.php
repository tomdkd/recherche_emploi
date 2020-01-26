<?php

namespace Drupal\mesoffres\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\mesoffres\Service\NodeService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MesOffresController extends ControllerBase {

  private $nodeService;

  public function __construct(NodeService $nodeService){
    $this->nodeService = $nodeService;
  }

  public static function create(ContainerInterface $container){
    return new static (
      $container->get('mesoffres.node_service'),
    );
  }

  public function list() {

    $config = \Drupal::configFactory()->getEditable('mesoffres.settings');

    if ($config->get('notification') != 1) {
      $this->messenger()->addWarning($this->t('Vous n\'avez pas activé la notification par mail. Soyez notifié des actions à mener en l\'activant dans la configuration'));
    }

    return [
      '#theme' => 'mesoffres_table',
      '#offres' => $this->nodeService->getAllNodes(),
      '#administratreur' => $this->nodeService->isAdministrator(),
      '#username' => \Drupal::currentUser()->getAccountName(),
    ];
  }

}
