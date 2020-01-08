<?php

namespace Drupal\mesoffres\Form;

use DateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mesoffres\Service\NodeService;
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

    $form['entreprise'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom de l\'entreprise'),
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
    // TODO: Implement submitForm() method.
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
