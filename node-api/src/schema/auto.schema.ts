import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type AutoDocument = HydratedDocument<Auto>;
@Schema()
export class Auto extends Document {
  @Prop()
  protected brand: string;

  @Prop()
  protected catalogId: string;

  @Prop()
  protected criteria: string;

  @Prop()
  protected description: string;

  @Prop()
  protected frame: string;

  @Prop()
  protected foreignId: string;

  @Prop()
  protected modelId: string;

  @Prop()
  protected modelName: string;

  @Prop()
  protected name: string;

  @Prop()
  protected vin: string;

  @Prop()
  protected code: string;

  @Prop()
  protected year: string;

  @Prop()
  protected parameters: object;

  @Prop()
  protected dateTime: Date | null;
}

export const AutoSchema = SchemaFactory.createForClass(Auto);
