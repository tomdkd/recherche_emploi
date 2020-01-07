<?php

namespace Drupal\ldm_generate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class LdmGenerateForm extends FormBase {

  public function getFormId(){
    return 'generer_ldm';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom de l\'entreprise'),
    ];

    $form['adresse'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Adresse'),
    ];

    $form['cp'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Code postal'),
    ];

    $form['ville'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ville'),
    ];

    $form['contact'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nom du contact'),
    ];

    $form['poste'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Poste recherché'),
    ];

    $form['type'] = [
      '#type' => 'select',
      '#options' => $this->getTypes(),
      '#title' => $this->t('Type de candidature'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state){

  }

  public function getTypes() {
    return [
      'spontanee' => 'Spontanée',
      'online' => 'Offre',
    ];
  }

}
