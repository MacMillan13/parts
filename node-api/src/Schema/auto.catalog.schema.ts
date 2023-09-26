import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument } from 'mongoose';
import { ObjectId } from "mongodb";

export type AutoCatalogDocument = HydratedDocument<AutoCatalog>;

@Schema()
export class AutoCatalog {
  @Prop({ type: ObjectId })
  protected id: ObjectId;

  @Prop({ type: String })
  protected catalogId: string;

  @Prop({ type: String })
  protected modelId: string;

  @Prop({ type: String })
  protected yearId: string;

  @Prop({ type: String })
  protected regionId: string;

  @Prop({ type: String })
  protected steeringId: string;

  @Prop({ type: String })
  protected seriesId: string;

  @Prop({ type: String })
  protected bodyTypeId: string;

  @Prop({ type: String })
  protected transmissionTypeId: string;

  @Prop({ type: String })
  protected exactModelId: string;

  @Prop({ type: String })
  protected engineId: string;

  @Prop({ type: Object })
  protected parameters: object;

  @Prop({ type: Object })
  protected carList: object;

  @Prop({ type: Date })
  protected dateTime: Date | null;

  @Prop({ type: String })
  protected sunroof: string;

  @Prop({ type: String })
  protected navigation: string;

  @Prop({ type: String })
  protected vsa: string;

  @Prop({ type: String })
  protected doorCount: string;

  @Prop({ type: String })
  protected abs: string;

  @Prop({ type: String })
  protected modificationId: string;

  @Prop({ type: String })
  protected productPeriod: string;

  @Prop({ type: String })
  protected engineCapacity: string;

  @Prop({ type: String })
  protected fuelType: string;

  @Prop({ type: String })
  protected carName: string;

  @Prop({ type: String })
  protected specSeries: string;

  @Prop({ type: String })
  protected bodyCode: string;

  @Prop({ type: String })
  protected transmission: string;

  @Prop({ type: String })
  protected grade: string;

  @Prop({ type: String })
  protected classification: string;

  @Prop({ type: String })
  protected autoParameters: string;

  @Prop({ type: String })
  protected specModelDate: string;

  @Prop({ type: String })
  protected specVinPart: string;

  @Prop({ type: String })
  protected specModification: string;

  @Prop({ type: String })
  protected specCatalog: string;

  @Prop({ type: String })
  protected year: string;

  @Prop({ type: String })
  protected model: string;
}

export const AutoCatalogSchema = SchemaFactory.createForClass(AutoCatalog);