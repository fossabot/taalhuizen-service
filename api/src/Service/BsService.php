<?php


namespace App\Service;


use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BsService
{
    private CommonGroundService $commonGroundService;
    private ParameterBagInterface $parameterBag;

    public function __construct(CommonGroundService $commonGroundService, ParameterBagInterface $parameterBag)
    {
        $this->commonGroundService = $commonGroundService;
        $this->parameterBag = $parameterBag;
    }

    public function sendPasswordResetMail(string $email, string $token)
    {
        $link = "{$this->parameterBag->get('app_domain')}/auth/resetpassword/$token";
        $message = [
            'content' => "Beste $email,<p>U heeft een wachtwoord-reset link aangevraagd voor de Taalhuizen applicatie.</p><p><a href='$link'>Klik hier om het wachtwoord te resetten</a></p><p>Met vriendelijke groet,</p><p>BiSC Taalhuizen</p>",
            'subject' => 'Wachtwoord reset op Taalhuizen',
            'sender' => 'info@taalhuizen-bisc.commonground.nu',
            'reciever' => $email,
            'status' => 'queued',
            'service' => '/services/30a1ccce-6ed5-4647-af04-d319b292e232',
        ];

        $this->commonGroundService->createResource($message, ['component' => 'bs', 'type' => 'messages']);
    }

    public function sendPasswordChangedEmail(string $username, array $contact)
    {
        $link = "{$this->parameterBag->get('app_domain')}/auth/forgotpassword";
        $message = [
            'content' => "Beste {$contact['givenName']},<p>Uw wachtwoord is succesvol gewijzigd.</p><p>Als u dit niet zelf gedaan hebt, vraag dan <a href='$link'>een nieuw wachtwoord aan</a><p>Met vriendelijke groet,</p><p>BiSC Taalhuizen</p>",
            'subject' => 'Uw wachtwoord op Taalhuizen werd veranderd',
            'sender' => 'info@taalhuizen-bisc.commonground.nu',
            'reciever' => $username,
            'status' => 'queued',
            'service' => '/services/30a1ccce-6ed5-4647-af04-d319b292e232',
        ];

        $this->commonGroundService->createResource($message, ['component' => 'bs', 'type' => 'messages']);
    }

    public function sendInvitation(string $email, string $token, array $contact, string $organizationUrl = null)
    {
        $link = "{$this->parameterBag->get('app_domain')}/auth/resetpassword/$token";

        if (!empty($organizationUrl)) {
            $organization = $this->commonGroundService->getResource($organizationUrl);
            $content = "Beste {$contact['givenName']},<p>Wij hebben een account voor je aangemaakt zodat je gebruik kunt maken van TOP voor {$organization['name']}. </p><p>Via de link in deze mail kan je een wachtwoord instellen en hiermee inloggen om de applicatie te gaan gebruiken. </p><p><a href='$link'>Klik hier om het wachtwoord in te stellen</a></p><p>Met vriendelijke groet,</p><p>TOP</p>";
        } else {
            $content = "Beste {$contact['givenName']},<p>Wij hebben een account voor je aangemaakt zodat je gebruik kunt maken van TOP. </p><p>Via de link in deze mail kan je een wachtwoord instellen en hiermee inloggen om de applicatie te gaan gebruiken. </p><p><a href='$link'>Klik hier om het wachtwoord in te stellen</a></p><p>Met vriendelijke groet,</p><p>TOP</p>";
        }

        $message = [
            'content' => $content,
            'subject' => 'U bent uitgenodigd, welkom!',
            'sender' => 'info@taalhuizen-bisc.commonground.nu',
            'reciever' => $email,
            'status' => 'queued',
            'service' => '/services/30a1ccce-6ed5-4647-af04-d319b292e232',
        ];

        $this->commonGroundService->createResource($message, ['component' => 'bs', 'type' => 'messages']);
    }
}
