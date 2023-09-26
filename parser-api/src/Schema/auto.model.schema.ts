import { ObjectId } from 'mongodb';
import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument } from 'mongoose';

export type AutoModelDocument = HydratedDocument<AutoModel>;

@Schema()
export class AutoModel {
  @Prop({ type: ObjectId })
  protected id: ObjectId;

  @Prop({ type: String })
  protected catalogId: string;

  @Prop({ type: Object })
  protected models: object;

  @Prop({ type: Date })
  protected dateTime: Date | null;
}

export const AutoModelSchema = SchemaFactory.createForClass(AutoModel);