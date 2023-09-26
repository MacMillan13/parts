import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument } from 'mongoose';
import { ObjectId } from 'mongodb';

export type PartCatalogDocument = HydratedDocument<PartCatalog>;

@Schema()
export class PartCatalog {
  @Prop({ type: ObjectId })
  protected id: ObjectId;

  @Prop({ type: Object })
  catalogData: object;

  @Prop({ type: String })
  catalogId: string;

  @Prop({ type: String })
  carId: string;

  @Prop()
  dateTime: Date | null;
}

export const PartCatalogSchema = SchemaFactory.createForClass(PartCatalog);
