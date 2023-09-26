import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { ObjectId } from 'mongodb';
import { HydratedDocument } from 'mongoose';

export type PartDocument = HydratedDocument<Part>;

@Schema()
export class Part {
  @Prop({ type: ObjectId })
  protected id: ObjectId;

  @Prop()
  protected partNumber: string;

  @Prop()
  protected name: string;

  @Prop()
  protected notice: string | null;

  @Prop()
  protected description: string;

  @Prop({ type: [String] })
  protected cross: string[];

  @Prop({ type: ObjectId })
  protected partSchemaId: ObjectId;

  @Prop()
  protected dateTime: Date | null;
}

export const PartSchema = SchemaFactory.createForClass(Part);