import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model } from 'mongoose';
import { Auto } from '../schema/auto.schema';
import { AutoModelService } from './auto.model.service';

@Injectable()
export class AutoService {
  constructor(
    @InjectModel('Auto') private readonly autoModel: Model<Auto>,
    private readonly autoModelService: AutoModelService,
    private readonly autoCatalogDataQuery: AutoCatalogDataQuery, // Import and inject AutoCatalogDataQuery
  ) {}

  async getAutoByModification(
    catalogId: string,
    modelName: string,
    year: string,
    modification: string,
  ): Promise<Auto> {
    let auto: Auto | null = await this.autoModel
      .findOne({
        catalogId,
        modelName,
        year,
        code: modification,
      })
      .exec();

    if (!auto) {
      const modelId = await this.autoModelService.getAutoModelId(
        catalogId,
        modelName,
      );

      const autoCatalog = new AutoCatalog();
      autoCatalog.catalogId = catalogId;
      autoCatalog.modelId = modelId;

      autoCatalog = await this.autoCatalogDataQuery.query(autoCatalog);
      const autoList = autoCatalog.carList || [];

      auto = autoList.find((oneAuto) => oneAuto.code === modification);
    }

    if (!auto) {
      // Handle the case when auto is still not found
      throw new Error('Auto not found');
    }

    return auto;
  }
}
