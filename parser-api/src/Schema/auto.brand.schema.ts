import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { HydratedDocument } from 'mongoose';
import { ObjectId } from "mongodb";

export type AutoBrandDocument = HydratedDocument<AutoBrand>;

@Schema()
export class AutoBrand {

  @Prop({ type: ObjectId })
  protected id: ObjectId;

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