<?php

namespace Drupal\mesoffres;

class Offre {

  private $nid;
  private $date;
  private $entreprise;
  private $intitule;
  private $nomcontact;
  private $mailcontact;
  private $reponse;

  /**
   * Offre constructor.
   * @param $nid
   * @param $date
   * @param $entreprise
   * @param $intitule
   * @param $nom_contact
   * @param $mail_contact
   * @param $reponse
   */
  public function __construct($nid, $date, $entreprise, $intitule, $nomcontact, $mailcontact, $reponse) {
    $this->nid = $nid;
    $this->date = $date;
    $this->entreprise = $entreprise;
    $this->intitule = $intitule;
    $this->nomcontact = $nomcontact;
    $this->mailcontact = $mailcontact;
    $this->reponse = $reponse;
  }


  /**
   * @return mixed
   */
  public function getNid()
  {
    return $this->nid;
  }

  /**
   * @param mixed $nid
   */
  public function setNid($nid)
  {
    $this->nid = $nid;
  }

  /**
   * @return mixed
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @param mixed $date
   */
  public function setDate($date)
  {
    $this->date = $date;
  }

  /**
   * @return mixed
   */
  public function getEntreprise()
  {
    return $this->entreprise;
  }

  /**
   * @param mixed $entreprise
   */
  public function setEntreprise($entreprise)
  {
    $this->entreprise = $entreprise;
  }

  /**
   * @return mixed
   */
  public function getIntitule()
  {
    return $this->intitule;
  }

  /**
   * @param mixed $intitule
   */
  public function setIntitule($intitule)
  {
    $this->intitule = $intitule;
  }

  /**
   * @return mixed
   */
  public function getNomContact()
  {
    return $this->nomcontact;
  }

  /**
   * @param mixed $nom_contact
   */
  public function setNomContact($nom_contact)
  {
    $this->nomcontact = $nom_contact;
  }

  /**
   * @return mixed
   */
  public function getMailContact()
  {
    return $this->mailcontact;
  }

  /**
   * @param mixed $mail_contact
   */
  public function setMailContact($mail_contact)
  {
    $this->mailcontact = $mail_contact;
  }

  /**
   * @return mixed
   */
  public function getReponse()
  {
    return $this->reponse;
  }

  /**
   * @param mixed $reponse
   */
  public function setReponse($reponse)
  {
    $this->reponse = $reponse;
  }

  public function checksendmail() {
    $today = new \DateTime();
    $offre_date = new \DateTime($this->getDate());

    $diff = $today->diff($offre_date);

    if ($diff->d >= 7 && $this->getReponse() != 'Positive') {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}
