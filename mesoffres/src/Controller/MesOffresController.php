<?php

namespace Drupal\mesoffres\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\mesoffres\Service\MailService;
use Drupal\mesoffres\Service\NodeService;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class MesOffresController extends ControllerBase
{

  private $nodeService;
  private $mailservice;

  public function __construct(NodeService $nodeService, MailService $mailservice){
    $this->nodeService = $nodeService;
    $this->mailservice = $mailservice;
  }

  public static function create(ContainerInterface $container)
  {
    return new static (
      $container->get('mesoffres.node_service'),
      $container->get('mesoffres.mail_service')
    );
  }

  public function list(){
    $config = \Drupal::configFactory()->getEditable('mesoffres.settings');
    $nodes = $this->nodeService->getAllNodes();
    $user_role = \Drupal::currentUser()->getRoles();

    if ($config->get('notification') != 1) {
      $url = Url::fromRoute('mesoffres.conf')->toString();
      $message = 'Vous n\'avez pas activé la notification par mail. ';
      $message .= 'Soyez notifié des actions à mener en l\'activant dans la <a href="' . $url . '">configuration</a>';
      $this->messenger()->addWarning($this->t($message));
    }
    else {
      if ($nodes != NULL && !in_array('administrator', $user_role)) {
        foreach ($nodes as $offre) {
          if ($offre->checksendmail()) {
            $alloffres[] = $offre->getEntreprise();
          }
        }
        $alloffres = implode(', ', $alloffres);
        $this->mailservice->sendMail($alloffres);
      }

      if (in_array('administrator', $user_role)) {
        $this->messenger()->addWarning($this->t('Envoi de mail désactivé en mode administrateur'));
      }
    }

    return [
      '#theme' => 'mesoffres_table',
      '#offres' => $nodes,
//      '#administratreur' => $this->nodeService->isAdministrator(),
      '#username' => \Drupal::currentUser()->getAccountName(),
    ];
  }

  public function delete($numero)
  {
    $node = Node::load($numero);
    $node->delete();

    $message = "L'offre a bien été supprimée";
    $this->messenger()->addMessage($this->t($message));

    return $this->redirect('mesoffres.list');
  }

  public function export()
  {
    $nodes = $this->nodeService->getAllNodes();

    if (!empty($nodes)) {
      $header = [
        'date' => $this->t('Date'),
        'entreprise' => $this->t('Nom de l\'entreprise'),
        'offre' => $this->t('Titre de l\'offre'),
        'contact' => $this->t('Nom du contact'),
        'mail' => $this->t('Mail du contact'),
        'reponse' => $this->t('Réponse'),
      ];

      $content = implode(';', $header);

      foreach ($nodes as $node) {
        $data = [
          'date' => $node->getDate(),
          'entreprise' => $node->getEntreprise(),
          'offre' => $node->getIntitule(),
          'contact' => $node->getNomContact(),
          'mail' => $node->getMailContact(),
          'reponse' => $node->getReponse(),
        ];

        $content .= "\n" . implode(';', $data);
      }

      $content = mb_convert_encoding($content, 'UTF-16LE', 'UTF-8');
      $response = new Response($content);

      $filename = 'mesoffres.csv';

      $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

      $response->headers->set('Content-Disposition', $disposition);
      $response->headers->set('Content-Type', 'text/csv; UTF-8');
      $response->headers->set('Content-Encoding', 'UTF-8');

      return $response;
    } else {
      $this->messenger()->addError($this->t('Vous n\'avez aucune offre enregistrée'));
      return $this->redirect('mesoffres.list');
    }

  }

}
