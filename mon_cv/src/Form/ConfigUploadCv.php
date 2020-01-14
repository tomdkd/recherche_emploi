<?php

namespace Drupal\mon_cv\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

class ConfigUploadCv extends ConfigFormBase {

  public function getFormId(){
    return 'config_upload_form';
  }

  protected function getEditableConfigNames(){
    return 'form_file';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    $form['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Mon CV'),
      '#upload_location' => 'public://cv/',
      '#multiple' => FALSE,
      "#required" => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    \Drupal::configFactory()->getEditable($this->getEditableConfigNames())
      ->set('file', $form_state->getValue('file'))
      ->save();

  }

  public function getFile($fileId) {

    $file = File::load($fileId);
    $fileUrl = $file->url();

    return $fileUrl;

  }

}
