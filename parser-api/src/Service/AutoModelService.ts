import { DocumentManager } from 'doctrine-odm-mongodb';
import { AutoModel } from '../Schema/AutoModel';

class AutoModelService {
  constructor(private documentManager: DocumentManager) {}

  getAutoModelId(catalogId: string, modelName: string): string {
    const autoModelRep = this.documentManager.getRepository(AutoModel);
    const autoModel = autoModelRep.findOneBy({ catalogId });
    const models = autoModel.getModels();
    let modelId: string | undefined;
    for (const model of models) {
      if (model.name.toLowerCase() === modelName) {
        modelId = model.id;
        break;
      }
    }
    if (!modelId) {
      throw new Error("Can't find an auto by model.");
    }
    return modelId;
  }
}