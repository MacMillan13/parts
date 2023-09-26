import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type PartCatalogDocument = HydratedDocument<PartSuggestionQuery>;
@Schema()
export class PartSuggestionQuery extends Document {
  @Prop({ type: Object })
  data: object;

  @Prop({ type: String })
  query: string;

  @Prop({ type: String })
  catalogId: string;

  @Prop({ type: Date, default: Date.now })
  dateTime: Date;
}

export const PartSuggestionQuerySchema =
  SchemaFactory.createForClass(PartSuggestionQuery);
