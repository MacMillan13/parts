import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type AutoModelDocument = HydratedDocument<AutoModel>;

@Schema()
export class AutoModel extends Document {
  @Prop({ type: String })
  protected catalogId: string;

  @Prop({ type: Object })
  protected models: object;

  @Prop({ type: Date })
  protected dateTime: Date | null;
}

export const AutoModelSchema = SchemaFactory.createForClass(AutoModel);
