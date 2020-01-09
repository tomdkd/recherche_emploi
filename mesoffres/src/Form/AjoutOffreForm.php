<?php

namespace Drupal\mesoffres\Form;

use DateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mesoffres\Service\NodeService;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function var_dump;

class AjoutOffreForm extends FormBase {

  private $nodeService;

  public function __construct(NodeService $nodeService){
    $this->nodeService = $nodeService;
  }

  public static function create(ContainerInterface $container){
    return new static (
      $container->get('mesoffres.node_service')
    );
  }

  public function getFormId(){
    return 'ajouter_offre';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $form['date'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Date de création de l\'offre'),
      '#default_value' => $this->getDate(),
      '#disabled' => TRUE,
    ];

    $form['entreprise'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom de l\'entreprise'),
      '#required' => TRUE,
    ];

    $form['poste'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Intitulé du poste'),
      '#required' => TRUE,
    ];

    $form['contact'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom et prénom du contact (Facultatif)'),
    ];

    $form['mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Adresse mail du contact'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enregistrer'),
      '#attributes' => [
        'class' => ['btn', 'btn-success'],
      ],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    $service = $this->nodeService;

    // Récupération des valeurs.
    $values = $form_state->getValues();

    $offre = new stdClass();
    $offre->intitule = $values['poste'];
    $offre->date = $values['date'];
    $offre->nom_entreprise = $values['entreprise'];
    $offre->mail_contact = $values['mail'];
    $offre->nom_contact = $values['contact'];
    $offre->reponse = FALSE;

    // On envoi les valeur pour créer un noeud.
    if ($service->createNode($offre)) {
      $message = $this->t('L\'offre a bien été enregistrée');
      $this->messenger()->addMessage($message);
    }
    else {
      $message = $this->t('Il y a eu une erreur dans l\'enregistrement');
      $this->messenger()->addError($message);
    }

    return $this->redirect('mesoffres.list');

  }

  public function getDate($date = NULL) {

    if ($date === NULL) {
      $date = '';
    }

    $datetime = new DateTime($date);
    $datetime = $datetime->format('d/m/Y');
    return $datetime;

  }

}
