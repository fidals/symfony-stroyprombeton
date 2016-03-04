<?php

namespace App\MainBundle\Extension;

use Symfony\Component\Filesystem\Filesystem;

class MailerService
{
    const MESSAGE_SITE_NAME = 'Stroyprombeton';
    const TEMPORARY_FILES_FOLDER_PATH = 'tmp/';

    private $fileSystem;
    private $mailer;
    private $transportReal;
    private $emailOrder;

    public function __construct(Filesystem $fileSystem, \Swift_Mailer $mailer, \Swift_Transport $transportReal, $mailOrder)
    {
        $this->fileSystem = $fileSystem;
        $this->mailer = $mailer;
        $this->transportReal = $transportReal;
        $this->emailOrder = $mailOrder;
    }

    /**
     * Метод формирования и отправки письма.
     *
     * @param $mailOptions
     */
    public function constructAndSendEmail($mailOptions)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject(self::MESSAGE_SITE_NAME.' | '.$mailOptions['mailTitle'])
            ->setTo($mailOptions['mailRecipients'])
            ->setFrom($this->emailOrder)
            ->setContentType('text/html')
            ->setBody($mailOptions['mailBody']);

        $mailFiles = $mailOptions['mailFiles'][0];

        if ($mailFiles !== null) {
            $message = $this->attachEmailFiles($message, $mailOptions['mailFiles']);
        }

        $this->mailer->send($message);

        if ($mailFiles !== null) {
            $this->removeEmailFiles($mailFiles);
        }
    }

    /**
     * Метод прикрепления файлов к письму и
     * удаления их после отправки письма.
     *
     * @param $message
     * @param $mailFiles
     */
    public function attachEmailFiles($message, $mailFiles)
    {
        foreach ($mailFiles as $file) {
            $fileName = $file->getClientOriginalName();
            $file->move(self::TEMPORARY_FILES_FOLDER_PATH, $fileName);
            $fileFullPath = self::TEMPORARY_FILES_FOLDER_PATH.$fileName;
            $message->attach(\Swift_Attachment::fromPath($fileFullPath));
        }

        return $message;
    }

    /**
     * Метод удаления файлов после отправки письма.
     *
     * @param $mailFiles
     */
    public function removeEmailFiles($mailFiles)
    {
        $this->mailer->getTransport()->getSpool()->flushQueue($this->transportReal);

        foreach ($mailFiles as $file) {
            $this->fileSystem->remove(self::TEMPORARY_FILES_FOLDER_PATH.$file->getClientOriginalName());
        }
    }
}
