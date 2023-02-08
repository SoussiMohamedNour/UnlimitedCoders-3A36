<?php

namespace App\Test\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilisateurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UtilisateurRepository $repository;
    private string $path = '/utilisateur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Utilisateur::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'utilisateur[email]' => 'Testing',
            'utilisateur[roles]' => 'Testing',
            'utilisateur[password]' => 'Testing',
            'utilisateur[role]' => 'Testing',
            'utilisateur[nom]' => 'Testing',
            'utilisateur[prenom]' => 'Testing',
            'utilisateur[adresse]' => 'Testing',
            'utilisateur[num_tel]' => 'Testing',
            'utilisateur[sexe]' => 'Testing',
            'utilisateur[cnam_med]' => 'Testing',
            'utilisateur[age]' => 'Testing',
            'utilisateur[dispo_cabinet]' => 'Testing',
            'utilisateur[date_naiss]' => 'Testing',
            'utilisateur[cin]' => 'Testing',
            'utilisateur[dispo_pharma]' => 'Testing',
            'utilisateur[num_pharmacid]' => 'Testing',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setRole('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setSexe('My Title');
        $fixture->setCnam_med('My Title');
        $fixture->setAge('My Title');
        $fixture->setDispo_cabinet('My Title');
        $fixture->setDate_naiss('My Title');
        $fixture->setCin('My Title');
        $fixture->setDispo_pharma('My Title');
        $fixture->setNum_pharmacid('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateur();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setRole('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setSexe('My Title');
        $fixture->setCnam_med('My Title');
        $fixture->setAge('My Title');
        $fixture->setDispo_cabinet('My Title');
        $fixture->setDate_naiss('My Title');
        $fixture->setCin('My Title');
        $fixture->setDispo_pharma('My Title');
        $fixture->setNum_pharmacid('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'utilisateur[email]' => 'Something New',
            'utilisateur[roles]' => 'Something New',
            'utilisateur[password]' => 'Something New',
            'utilisateur[role]' => 'Something New',
            'utilisateur[nom]' => 'Something New',
            'utilisateur[prenom]' => 'Something New',
            'utilisateur[adresse]' => 'Something New',
            'utilisateur[num_tel]' => 'Something New',
            'utilisateur[sexe]' => 'Something New',
            'utilisateur[cnam_med]' => 'Something New',
            'utilisateur[age]' => 'Something New',
            'utilisateur[dispo_cabinet]' => 'Something New',
            'utilisateur[date_naiss]' => 'Something New',
            'utilisateur[cin]' => 'Something New',
            'utilisateur[dispo_pharma]' => 'Something New',
            'utilisateur[num_pharmacid]' => 'Something New',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getRole());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getNum_tel());
        self::assertSame('Something New', $fixture[0]->getSexe());
        self::assertSame('Something New', $fixture[0]->getCnam_med());
        self::assertSame('Something New', $fixture[0]->getAge());
        self::assertSame('Something New', $fixture[0]->getDispo_cabinet());
        self::assertSame('Something New', $fixture[0]->getDate_naiss());
        self::assertSame('Something New', $fixture[0]->getCin());
        self::assertSame('Something New', $fixture[0]->getDispo_pharma());
        self::assertSame('Something New', $fixture[0]->getNum_pharmacid());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Utilisateur();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setRole('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setSexe('My Title');
        $fixture->setCnam_med('My Title');
        $fixture->setAge('My Title');
        $fixture->setDispo_cabinet('My Title');
        $fixture->setDate_naiss('My Title');
        $fixture->setCin('My Title');
        $fixture->setDispo_pharma('My Title');
        $fixture->setNum_pharmacid('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/utilisateur/');
    }
}
