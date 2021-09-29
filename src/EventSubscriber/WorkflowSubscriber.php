<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Workflow\Event\Event;

class WorkflowSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function newToyRequest(Event $event)
    {
        $email = (new Email())
            ->from($event->getSubject()->getUser()->getEmail())
            ->to('dad@test.fr')
            ->addTo('mum@test.fr')
            ->subject("Demande de jouet - {$event->getSubject()->getName()}")
            ->text("Bonjour Maman et Papa, merci de commander au Père Noël le jouet suivant : {$event->getSubject()->getName()}");

        $this->mailer->send($email);
    }

    public function dadToyValidate(Event $event)
    {
        $email = (new Email())
            ->from('dad@test.fr')
            ->to('mum@test.fr')
            ->subject('Validation demande !')
            ->text("J'ai validé pour le jouet demandé : {$event->getSubject()->getName()} et toi ?");

        $this->mailer->send($email);
    }

    public function mumToyValidate(Event $event)
    {
        $email = (new Email())
            ->from('mum@test.fr')
            ->to('dad@test.fr')
            ->subject("Validation demande !")
            ->text("J'ai validé pour le jouet demandé : {$event->getSubject()->getName()} et toi ?");

        $this->mailer->send($email);
    }

    public function parentValidate(Event $event)
    {
        $email = (new Email())
            ->from('mum@test.fr')
            ->to('kid@test.fr')
            ->subject("jouet - {$event->getSubject()->getName()}")
            ->text("Papa et maman on ajouté le jouet {$event->getSubject()->getName()} à ta liste de noël !");

        $this->mailer->send($email);
    }

    public function letterSent(Event $event)
    {
        $email = (new Email())
            ->from('mum@test.fr')
            ->to('kid@test.fr')
            ->subject('lettre envoyée')
            ->text("Papa et maman viennent tout juste d'envoyer ta lettre au père noël !");

        $this->mailer->send($email);
    }

    public function toyReceived(Event $event)
    {
        $email = (new Email())
            ->from('papa.noel@laponie.com')
            ->to($event->getSubject()->getUser()->getEmail())
            ->subject('Arrivée imminente de tes cadeaux, oh oh oh !')
            ->text('Tes jouets seront bien sous la sapin pour noël, oh oh oh !');

        $this->mailer->send($email);
    }

    /**
     * Ecoute des évènements 
     * - [newToyRequest]  lorsque l'enfant commmande un jouet les deux parents recoivent un email
     * - [dadToyValidate] lorsque le papa valide le jouet il envoie un email à la maman
     * - [mumToyValidate] lorsque la maman valide le jouet elle envoie un email au papa
     * - [parentValidate] lorsque la commande est validée (ou refusé) par les parents l'enfant recoit un email
     * - [letterSent]     lorsque la lettre est envoyée au père noël l'enfant recoit un message des parents
     * - [toyReceived]    lorsque le père noël est dans son traineau et qu'il part pour apporter les cadeaux, l'enfant recoit un mail
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.toy_request.leave.request' => 'newToyRequest',
            'workflow.toy_request.enter.dad_ok' => 'dadToyValidate',
            'workflow.toy_request.enter.mum_ok' => 'mumToyValidate',
            'workflow.toy_request.enter.order' => 'parentValidate',
            'workflow.toy_request.leave.order' => 'letterSent',
            'workflow.toy_request.entered.received' => 'toyReceived'
        ];
    }
}
