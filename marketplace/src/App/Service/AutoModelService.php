<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoModelDataQuery;
use BitBag\OpenMarketplace\App\Document\AutoModel;
use Doctrine\ODM\MongoDB\DocumentManager;

class AutoModelService
{
    public function __construct(private DocumentManager $documentManager, private AutoModelDataQuery $autoModelDataQuery)
    {
    }

    /**
     * @param string $catalogId
     * @param string $modelName
     * @return string
     * @throws \Exception
     */
    public function getAutoModelId(string $catalogId, string $modelName): string
    {
        $autoBrand = $this->autoModelDataQuery->query($catalogId);

        foreach ($autoBrand->getModels() as $model) {
            if ($model['code'] === $modelName) {
                $modelId = $model['id'];
                break;
            }
        }

        if (empty($modelId)) {
            throw new \Exception("Can't find an auto by model.");
        }

        return $modelId;
    }
}
