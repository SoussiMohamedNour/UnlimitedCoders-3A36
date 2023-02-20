<?php

namespace App\Test\Controller;

use App\Entity\Facteur;
use App\Repository\FacteurRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FacteurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private FacteurRepository $repository;
    private string $path = '/facteurcrud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Facteur::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facteur index');

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
            'facteur[cin]' => 'Testing',
            'facteur[nom]' => 'Testing',
            'facteur[prenom]' => 'Testing',
            'facteur[id_patient]' => 'Testing',
            'facteur[id_medicament]' => 'Testing',
            'facteur[nom_med]' => 'Testing',
            'facteur[dosage]' => 'Testing',
            'facteur[prix]' => 'Testing',
        ]);

        self::assertResponseRedirects('/facteurcrud/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facteur();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setId_patient('My Title');
        $fixture->setId_medicament('My Title');
        $fixture->setNom_med('My Title');
        $fixture->setDosage('My Title');
        $fixture->setPrix('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facteur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facteur();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setId_patient('My Title');
        $fixture->setId_medicament('My Title');
        $fixture->setNom_med('My Title');
        $fixture->setDosage('My Title');
        $fixture->setPrix('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'facteur[cin]' => 'Something New',
            'facteur[nom]' => 'Something New',
            'facteur[prenom]' => 'Something New',
            'facteur[id_patient]' => 'Something New',
            'facteur[id_medicament]' => 'Something New',
            'facteur[nom_med]' => 'Something New',
            'facteur[dosage]' => 'Something New',
            'facteur[prix]' => 'Something New',
        ]);

        self::assertResponseRedirects('/facteurcrud/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCin());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getId_patient());
        self::assertSame('Something New', $fixture[0]->getId_medicament());
        self::assertSame('Something New', $fixture[0]->getNom_med());
        self::assertSame('Something New', $fixture[0]->getDosage());
        self::assertSame('Something New', $fixture[0]->getPrix());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Facteur();
        $fixture->setCin('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setId_patient('My Title');
        $fixture->setId_medicament('My Title');
        $fixture->setNom_med('My Title');
        $fixture->setDosage('My Title');
        $fixture->setPrix('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/facteurcrud/');
    }
}
