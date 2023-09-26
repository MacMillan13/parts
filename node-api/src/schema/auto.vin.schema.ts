import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';
import { ObjectId } from 'mongodb';

export type AutoCatalogDocument = HydratedDocument<AutoVin>;

@Schema()
export class AutoVin extends Document {
  @Prop({ type: Object })
  protected autoData: object;

  @Prop({ type: String })
  protected vinCode: string;

  @Prop({ type: ObjectId }) // Assuming ObjectId as the type for catalogId, update it if necessary
  protected catalogId: ObjectId;

  @Prop({ type: Boolean })
  protected exactMatch: boolean;

  @Prop({ type: Date })
  protected dateTime: Date | null;
}

export const AutoVinSchema = SchemaFactory.createForClass(AutoVin);
