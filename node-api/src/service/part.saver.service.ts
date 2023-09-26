import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model } from 'mongoose';
import { Part } from '../schema/part.schema';

@Injectable()
export class PartSaverService {
  constructor(
    @InjectModel('Part') private readonly partModel: Model<Part>,
    private readonly arrayHelper: ArrayHelper, // Import and inject ArrayHelper
  ) {}

  async savePartFromSchema(
    partSchema: PartSchema,
    responseArray: Array<Record<string, any>>,
  ): Promise<void> {
    for (const partGroups of responseArray['partGroups']) {
      const parts = this.arrayHelper.arrayUniqueByKey(
        partGroups['parts'],
        'number',
      );

      for (const part of parts) {
        const partNumber = part['number'].trim();

        let partValue: Part | null = await this.partModel.findOne({
          partNumber,
        }).exec();

        if (!partValue) {
          const newPart = new this.partModel({
            name: part['name'],
            partSchemaId: partSchema._id,
            partNumber,
            description: part['description'],
            notice: part['notice'],
            dateTime: new Date(),
          });

          for (const crossPart of parts) {
            const crossPartNumber = crossPart['number'].trim();
            if (
              partNumber !== crossPartNumber &&
              part['positionNumber'] == crossPart['positionNumber']
            ) {
              newPart.cross.push(crossPartNumber);
            }
          }

          await newPart.save();
        }
      }
    }
  }
}
