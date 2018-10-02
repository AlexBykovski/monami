<?php

namespace App\Helper;

use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;

class UserSupportHelper
{
    const ADMIN_EMAIL = "info@monami.by";
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * LoginHelper constructor.
     * @param EntityManagerInterface $em
     * @param Swift_Mailer $mailer
     */
    public function __construct(
        EntityManagerInterface $em,
        Swift_Mailer $mailer
    )
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function sendEmail(Feedback $feedback)
    {
        $fromAddress = $feedback->getUser() ? $feedback->getUser()->getEmail() : "guest_message@monami.by";

        $message = (new \Swift_Message('Support User Question'))
            ->setFrom($fromAddress)
            ->setTo("Kirillooo888@gmail.kom")
            ->setBody(
                $feedback->getMessage()
            );

        $this->mailer->send($message);
    }
}