import { InjectModel } from '@nestjs/mongoose';
import { Model } from 'mongoose';
import { AutoModel } from '../schema/auto.model.schema';
import { Injectable } from '@nestjs/common';

@Injectable()
export class AutoModelService {
  constructor(
    @InjectModel(AutoModel.name) private AutoModel: Model<AutoModel>,
  ) {}

  getAutoModelId(catalogId: string, modelName: string): string {
    const autoModel = this.AutoModel.findOne({ catalogId }).exec();
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
