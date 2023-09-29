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
    public function __construct(private ArrayHelper $arrayHelper, private DocumentManager $documentManager)
    {
    }

    /**
     * @param PartSchema $partSchema
     * @param array $responseArray
     * @return void
     * @throws \MongoException
     */
    public function savePartFromSchema(PartSchema $partSchema, array $responseArray): void
    {
        $partRep = $this->documentManager->getRepository(Part::class);

        foreach ($responseArray['partGroups'] as $partGroups) {

            $parts = $this->arrayHelper->arrayUniqueByKey($partGroups['parts'], 'number');

            foreach ($parts as $part) {

                if (empty($part['number'])) {
                    continue;
                }

                $partNumber = trim($part['number']);

                $partValue = $partRep->findOneBy(['partNumber' => $partNumber]);

                if (empty($partValue)) {
                    $newPart = new Part();
                    $newPart->setName($part['name'])
                        ->setPartSchemaId($partSchema->getId())
                        ->setPartNumber(trim($part['number']))
                        ->setDescription($part['description'])
                        ->setNotice($part['notice'])
                        ->setDateTime();

                    foreach ($parts as $crossPart) {
                        $crossPartNumber = trim($crossPart['number']);
                        if ($partNumber !== $crossPartNumber && $part['positionNumber'] == $crossPart['positionNumber']) {
                            $newPart->setCross($crossPartNumber);
                        }
                    }

                    $this->documentManager->persist($newPart);
                }
            }
        }
    }
}
