import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type AutoBrandDocument = HydratedDocument<AutoBrand>;

@Schema()
export class AutoBrand extends Document {
  @Prop()
  protected carId: string;

  @Prop()
  protected name: string;

  @Prop()
  protected modelsCount: number;

  @Prop()
  protected dateTime: Date | null;
}

export const AutoBrandSchema = SchemaFactory.createForClass(AutoBrand);
