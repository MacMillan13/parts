import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type PartSuggestionDocument = HydratedDocument<PartSuggestion>;

@Schema()
export class PartSuggestion extends Document {
  @Prop({ type: String })
  catalogId: string;

  @Prop({ type: String })
  sid: string;

  @Prop({ type: String })
  carId: string;

  @Prop({ type: Object })
  data: object;

  @Prop({ type: Date, default: Date.now })
  dateTime: Date;
}

export const PartSuggestionSchema =
  SchemaFactory.createForClass(PartSuggestion);
