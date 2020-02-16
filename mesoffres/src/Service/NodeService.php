<?php

namespace Drupal\mesoffres\Service;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\mesoffres\Offre;
use const SORT_ASC;

class NodeService {

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
      $nid = $node->id();
      $date = $node->get('field_date')->value;
      $entreprise = $node->get('field_nom_entreprise')->value;
      $intitule = $node->get('field_titre_offre')->value;
      $nom_contact = $node->get('field_nom_contact')->value;
      $mail_contact = $node->get('field_mail_contact')->value;
      $reponse = $node->get('field_reponse')->value ? 'Positive' : 'Negative / Pas de réponse';

      $offre = new Offre($nid, $date, $entreprise, $intitule, $nom_contact, $mail_contact, $reponse);
      $offres[] = $offre;
    }

    return $offres;
  }

  public function calculerPages($nbParPage, $nbNodes) {
    $nbPages = ceil($nbNodes / $nbParPage);
    return $nbPages;
  }

}
