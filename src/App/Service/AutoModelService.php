<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\Document\AutoModel;
use Doctrine\ODM\MongoDB\DocumentManager;

class AutoModelService
{
    public function __construct(private DocumentManager $documentManager)
    {
    }

    /**
     * @param string $modelName
     * @return string
     * @throws \Exception
     */
    public function getAutoModelId(string $catalogId, string $modelName): string
    {
        $autoModelRep = $this->documentManager->getRepository(AutoModel::class);
        $autoModel = $autoModelRep->findOneBy(['catalogId' => $catalogId]);
        $models = $autoModel->getModels();

        foreach ($models as $model) {
            if (strtolower($model['name']) === $modelName) {
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
