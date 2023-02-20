<?php

namespace App\Test\Controller;

use App\Entity\FicheAssurance;
use App\Repository\FicheAssuranceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FicheAssuranceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private FicheAssuranceRepository $repository;
    private string $path = '/fiche/assurancecrud/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(FicheAssurance::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('FicheAssurance index');

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
            'fiche_assurance[CIN]' => 'Testing',
            'fiche_assurance[nom]' => 'Testing',
            'fiche_assurance[prenom]' => 'Testing',
            'fiche_assurance[addresse]' => 'Testing',
            'fiche_assurance[matricule_cnam]' => 'Testing',
            'fiche_assurance[matricule_fiscal]' => 'Testing',
            'fiche_assurance[honoraires]' => 'Testing',
            'fiche_assurance[designation]' => 'Testing',
            'fiche_assurance[date]' => 'Testing',
            'fiche_assurance[total]' => 'Testing',
        ]);

        self::assertResponseRedirects('/fiche/assurancecrud/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new FicheAssurance();
        $fixture->setCIN('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAddresse('My Title');
        $fixture->setMatricule_cnam('My Title');
        $fixture->setMatricule_fiscal('My Title');
        $fixture->setHonoraires('My Title');
        $fixture->setDesignation('My Title');
        $fixture->setDate('My Title');
        $fixture->setTotal('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('FicheAssurance');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new FicheAssurance();
        $fixture->setCIN('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAddresse('My Title');
        $fixture->setMatricule_cnam('My Title');
        $fixture->setMatricule_fiscal('My Title');
        $fixture->setHonoraires('My Title');
        $fixture->setDesignation('My Title');
        $fixture->setDate('My Title');
        $fixture->setTotal('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'fiche_assurance[CIN]' => 'Something New',
            'fiche_assurance[nom]' => 'Something New',
            'fiche_assurance[prenom]' => 'Something New',
            'fiche_assurance[addresse]' => 'Something New',
            'fiche_assurance[matricule_cnam]' => 'Something New',
            'fiche_assurance[matricule_fiscal]' => 'Something New',
            'fiche_assurance[honoraires]' => 'Something New',
            'fiche_assurance[designation]' => 'Something New',
            'fiche_assurance[date]' => 'Something New',
            'fiche_assurance[total]' => 'Something New',
        ]);

        self::assertResponseRedirects('/fiche/assurancecrud/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCIN());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getAddresse());
        self::assertSame('Something New', $fixture[0]->getMatricule_cnam());
        self::assertSame('Something New', $fixture[0]->getMatricule_fiscal());
        self::assertSame('Something New', $fixture[0]->getHonoraires());
        self::assertSame('Something New', $fixture[0]->getDesignation());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getTotal());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new FicheAssurance();
        $fixture->setCIN('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setAddresse('My Title');
        $fixture->setMatricule_cnam('My Title');
        $fixture->setMatricule_fiscal('My Title');
        $fixture->setHonoraires('My Title');
        $fixture->setDesignation('My Title');
        $fixture->setDate('My Title');
        $fixture->setTotal('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/fiche/assurancecrud/');
    }
}
