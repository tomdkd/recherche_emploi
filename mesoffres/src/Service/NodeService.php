<?php

namespace Drupal\mesoffres\Service;

use Drupal;
use Drupal\node\Entity\Node;
use function in_array;
use const SORT_ASC;

class NodeService {

  public function isAdministrator() {
    $user = Drupal::currentUser();
    $role = $user->isAuthenticated();
    return $role;
  }

  public function createNode(Object $offre) {
    $valeurs = [
      'type' => 'offre',
      'title' => $offre->intitule,
      'field_date' => $offre->date,
      'field_titre_offre' => $offre->intitule,
      'field_nom_entreprise' => $offre->nom_entreprise,
      'field_mail_contact' => $offre->mail_contact,
      'field_nom_contact' => $offre->nom_contact,
      'field_reponse' => $offre->reponse,
    ];
    $node = Node::create($valeurs);

    if ($node->save()) {
      return TRUE;
    }
  }

  public function getAllNodes() {
    $nids = Drupal::entityQuery('node')->condition('type', 'offre')->execute();
    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $offres[] = [
        'intitule' => $node->get('field_titre_offre')->value,
        'date' => $node->get('field_date')->value,
        'entreprise' => $node->get('field_nom_entreprise')->value,
        'mail_contact' => $node->get('field_mail_contact')->value,
        'nom_contact' => $node->get('field_nom_contact')->value,
        'reponse' => $node->get('field_reponse')->value ? 'Positive' : 'Negative / Pas de réponse',
        'nid' => $node->id(),
        ];
    }

    $this->array_sort($offres, 'date', SORT_DESC);
    return $offres;
  }

  function array_sort($array, $colonne, $ordre){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          foreach ($v as $k2 => $v2) {
            if ($k2 == $colonne) {
              $sortable_array[$k] = $v2;
            }
          }
        } else {
          $sortable_array[$k] = $v;
        }
      }

      switch ($ordre) {
        case SORT_ASC:
          asort($sortable_array);
          break;
        case SORT_DESC:
          arsort($sortable_array);
          break;
      }

      foreach ($sortable_array as $k => $v) {
        $new_array[$k] = $array[$k];
      }
    }

    return $new_array;
  }

  public function calculerPages($nbParPage, $nbNodes) {
    $nbPages = ceil($nbNodes / $nbParPage);
    return $nbPages;
  }

}
