import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document } from 'mongoose';
import { HydratedDocument } from 'mongoose';

export type AutoModelDocument = HydratedDocument<PartCatalogCriteria>;

@Schema()
export class PartCatalogCriteria extends Document {
  @Prop({ type: Object })
  catalogData: object;

  @Prop()
  catalogId: string;

  @Prop()
  carId: string;

  @Prop()
  criteria: string;

  @Prop()
  dateTime: Date | null;
}

export const PartCatalogCriteriaSchema = SchemaFactory.createForClass(PartCatalogCriteria);