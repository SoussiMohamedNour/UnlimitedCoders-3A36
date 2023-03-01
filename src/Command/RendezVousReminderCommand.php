<?php
namespace App\Command;

use App\Repository\RendezVousRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use DateTime;
use DateInterval;


class RendezVousReminderCommand extends Command
{
    protected static $defaultName = 'app:rendezvous:reminder';
    protected static $defaultDescription = 'Send a reminder email before a day of the rendez-vous.';

    private $rendezVousRepository;
    private $utilisateurRepository;
    private $mailer;

    public function __construct(RendezVousRepository $rendezVousRepository, UtilisateurRepository $utilisateurRepository, MailerInterface $mailer, \Twig\Environment $twig)
    {
        $this->rendezVousRepository = $rendezVousRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->mailer = $mailer;
        $this->twig = $twig;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('app:rendezvous:reminder')
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get current date and time
        $now = new DateTime();

        // Add one day to current date
        $tomorrow = (new DateTime())->add(new DateInterval('P1D'));

        // Fetch all rendez-vous scheduled for tomorrow
        $rendezVousList = $this->rendezVousRepository->findRendezVousBetweenDates($now, $tomorrow);

        // Loop through the rendez-vous list
        foreach ($rendezVousList as $rendezVous) {
            // Get the associated utilisateur object
            $utilisateur = $this->utilisateurRepository->find($rendezVous->getUtilisateurId());

            // Get the email address of the user
            $email = $utilisateur->getEmail();

            // Send a reminder email to the user
            $email = (new Email())
                ->from('your_email@example.com')
                ->to($email)
                ->subject('Reminder for your rendez-vous tomorrow')
                ->text('Dear ' . $utilisateur->getNom() . ', your rendez-vous is scheduled for tomorrow. Please don\'t forget to attend it.');

            $this->mailer->send($email);
        }

        $output->writeln('Reminder emails sent successfully.');

        return Command::SUCCESS;
    }
}