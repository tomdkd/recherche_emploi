<?php

namespace Drupal\mesoffres\Service;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\mesoffres\Offre;
use const SORT_ASC;

class NodeService {

  public function createNode(Object $offre) {
    $date = str_replace('/', '-', $offre->date);
    $date = new \DateTime($date);
    $valeurs = [
      'type' => 'offre',
      'title' => $offre->nom_entreprise . '-' . $offre->intitule,
      'field_date' => $date->format('Y-m-d'),
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

  public function updateNode($type, $offre) {
    $nid = $offre->nid;
    $node = Node::load($nid);
    $node->field_date = $offre->date;
    $node->field_entreprise = $offre->entreprise;
    $node->field_titre_offre = $offre->intitule;
    $node->field_nom_contact = $offre->nom_contact;
    $node->field_mail_contact = $offre->mail_contact;
    $node->field_reponse = $offre->reponse;

    if ($node->save()) {
      return Drupal::messenger()->addMessage('L\'offre a bien été modifiée');
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

  public function getNodeByNid($nid) {
    $node = Node::load($nid);
    return $node;
  }

}
