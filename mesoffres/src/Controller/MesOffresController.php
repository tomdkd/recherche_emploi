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
      $container->get('mesoffres.node_service')
    );
  }

  public function list() {
    return [
      '#theme' => 'mesoffres_table',
      '#offres' => $this->nodeService->getAllNodes(),
      '#administratreur' => $this->nodeService->isAdministrator(),
    ];
  }

}
