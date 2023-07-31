<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\Document\Part;
use BitBag\OpenMarketplace\App\Document\PartSchema;
use BitBag\OpenMarketplace\App\Helper\ArrayHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoId;

class PartSaverService
{
    public function __construct(private ArrayHelper $arrayHelper)
    {
    }

    public function savePartFromSchema(PartSchema $partSchema, array $responseArray, DocumentManager $documentManager)
    {
        $partRep = $documentManager->getRepository(Part::class);

        foreach ($responseArray['partGroups'] as $partGroups) {

            $parts = $this->arrayHelper->arrayUniqueByKey($partGroups['parts'], 'number');

            foreach ($parts as $part) {
                $partValue = $partRep->findOneBy(['partNumber' => $part['number']]);

                if (empty($partValue)) {
                    $newPart = new Part();
                    $newPart->setName($part['name'])
                        ->setPartSchemaId(new MongoId($partSchema->getId()))
                        ->setPartNumber(trim($part['number']))
                        ->setDescription($part['description'])
                        ->setNotice($part['notice'])
                        ->setName($part['name'])
                        ->setDateTime();

                    foreach ($parts as $crossPart) {
                        if ($part['number'] !== $crossPart['number'] && $part['positionNumber'] == $crossPart['positionNumber']) {
                            $newPart->setCross($crossPart['number']);
                        }
                    }

                    $documentManager->persist($newPart);
                }
            }
        }
    }
}
