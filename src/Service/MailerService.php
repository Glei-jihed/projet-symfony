<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MailerService 
{

    public function __construct(private MailerInterface $mailer ){}

    public function sendEmail(
        $to = 'glei.jihed@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Update in our System',
    ): void
    {
        $email = (new Email())
            ->from('work.symfony@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            //->text('Sending emails is fun again!')
            ->html($content);

        //$mailer->send($email);
         $this->mailer->send($email);

        // ...
    }

}