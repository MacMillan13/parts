<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\Document\Part;
use BitBag\OpenMarketplace\App\Document\PartSchema;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoId;

class PartSaverService
{
    public function savePartFromSchema(PartSchema $partSchema, array $responseArray, DocumentManager $documentManager)
    {
        $partRep = $documentManager->getRepository(Part::class);

        foreach ($responseArray['partGroups'] as $partGroups) {
            foreach ($partGroups['parts'] as $part) {
                $partValue = $partRep->findOneBy(['partNumber' => $part['number']]);

                if (empty($partValue)) {
                    $newPart = new Part();
                    $newPart->setName($part['name'])
                        ->setPartSchemaId(new MongoId($partSchema->getId()))
                        ->setPartNumber($part['number'])
                        ->setDescription($part['description'])
                        ->setNotice($part['notice'])
                        ->setName($part['name'])
                        ->setDateTime();

                    $documentManager->persist($newPart);
                }
            }
        }
    }
}
