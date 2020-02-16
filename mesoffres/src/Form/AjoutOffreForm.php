<?php

namespace Drupal\mesoffres\Form;

use DateTime;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\mesoffres\Offre;
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
    $nid = \Drupal::routeMatch()->getParameter('nid');
    if ($nid) {
      $node = $this->nodeService->getNodeByNid($nid);
      $offre = new Offre($node->id(),
        $node->get('field_date')->value,
        $node->get('field_nom_entreprise')->value,
        $node->get('field_titre_offre')->value,
        $node->get('field_nom_contact')->value,
        $node->get('field_mail_contact')->value,
        $node->get('field_reponse')->value
      );
    }

    $form['nid'] = [
      '#type' => 'textfield',
      '#default_value' => $nid,
      '#access' => FALSE,
    ];

    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date de création de l\'offre'),
      '#default_value' => $offre ? $offre->getDate() : $this->getDate(),
      '#disabled' => $offre ? FALSE : TRUE,
    ];

    $form['entreprise'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom de l\'entreprise'),
      '#required' => TRUE,
      '#default_value' => $offre ? $offre->getEntreprise() : '',
    ];

    $form['poste'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Intitulé du poste'),
      '#required' => TRUE,
      '#default_value' => $offre ? $offre->getIntitule() : '',
    ];

    $form['contact'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom et prénom du contact (Facultatif)'),
      '#default_value' => $offre ? $offre->getNomContact() : '',
    ];

    $form['mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Adresse mail du contact'),
      '#required' => TRUE,
      '#default_value' => $offre ? $offre->getMailContact() : '',
    ];

    if ($nid) {
      $form['reponse'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Réponse reçue ?'),
        '#default_value' => $offre->getReponse() == '1' ? TRUE : FALSE,
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enregistrer'),
      '#attributes' => [
        'class' => ['btn', 'btn-success'],
      ],
    ];

    $url = Url::fromRoute('mesoffres.list')->toString();
    $value = $this->t('Back');
    $form['back'] = [
      '#markup' => '<a href="' . $url .'" class="btn btn-info">' . $value . '</a>',
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
    $offre->reponse = $values['reponse'] == '1' ? TRUE : FALSE;

    if ($values['nid']) {
      // Modification
      $offre->nid = $values['nid'];
      $this->nodeService->updateNode('offre', $offre);
    }
    else {
      // Création.
      if ($service->createNode($offre)) {
        $message = $this->t('L\'offre a bien été enregistrée');
        $this->messenger()->addMessage($message);
      }
      else {
        $message = $this->t('Il y a eu une erreur dans l\'enregistrement');
        $this->messenger()->addError($message);
      }
    }

    $form_state->setRedirect('mesoffres.list');
    return;

  }

  public function getDate($date = NULL) {

    if ($date === NULL) {
      $date = '';
    }

    $datetime = new DateTime($date);
    $datetime = $datetime->format('Y-m-d');
    return $datetime;

  }

}
