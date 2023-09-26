import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type PartCatalogDocument = HydratedDocument<PartCatalogGroup>;

@Schema()
export class PartCatalogGroup extends Document {
  @Prop({ type: Object })
  catalogData: object;

  @Prop({ type: String })
  catalogId: string;

  @Prop({ type: String })
  carId: string;

  @Prop({ type: String })
  group: string;

  @Prop({ type: String })
  groupId: string;

  @Prop({ type: Date })
  dateTime: Date | null;
}

export const PartCatalogGroupSchema =
  SchemaFactory.createForClass(PartCatalogGroup);
