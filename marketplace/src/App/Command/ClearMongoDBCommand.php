<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Command;

use BitBag\OpenMarketplace\App\Document\Auto;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Document\AutoModel;
use BitBag\OpenMarketplace\App\Document\AutoVin;
use BitBag\OpenMarketplace\App\Document\Part;
use BitBag\OpenMarketplace\App\Document\PartCatalog;
use BitBag\OpenMarketplace\App\Document\PartCatalogCriteria;
use BitBag\OpenMarketplace\App\Document\PartCatalogCriteriaGroup;
use BitBag\OpenMarketplace\App\Document\PartCatalogGroup;
use BitBag\OpenMarketplace\App\Document\PartSchema;
use BitBag\OpenMarketplace\App\Document\PartSuggestion;
use BitBag\OpenMarketplace\App\Document\PartSuggestionQuery;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:clear-mongodb')]
class ClearMongoDBCommand extends Command
{
    /**
     * @param string|null $name
     * @param DocumentManager $documentManager
     */
    public function __construct(private DocumentManager $documentManager, string $name = null)
    {
        parent::__construct($name);
    }

    /**
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->documentManager->getSchemaManager()->dropDocumentCollection(Auto::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(AutoModel::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(AutoCatalog::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(AutoVin::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(Part::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartCatalog::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartCatalogCriteria::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartCatalogCriteriaGroup::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartCatalogGroup::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartSchema::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartSuggestion::class);
        $this->documentManager->getSchemaManager()->dropDocumentCollection(PartSuggestionQuery::class);

        return Command::SUCCESS;
    }
}
