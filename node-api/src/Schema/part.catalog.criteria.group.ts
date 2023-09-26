import { Prop, Schema, SchemaFactory } from '@nestjs/mongoose';
import { Document, HydratedDocument } from 'mongoose';

export type PartCatalogCriteriaDocument =
  HydratedDocument<PartCatalogCriteriaGroup>;

@Schema()
export class PartCatalogCriteriaGroup extends Document {
  @Prop({ type: Object })
  catalogData: object;

  @Prop({ type: String })
  catalogId: string;

  @Prop({ type: String })
  carId: string;

  @Prop({ type: String })
  groupId: string;

  @Prop({ type: String })
  group: string;

  @Prop({ type: String })
  criteria: string;

  @Prop({ type: Date })
  dateTime: Date | null;
}

export const PartCatalogCriteriaGroupSchema = SchemaFactory.createForClass(
  PartCatalogCriteriaGroup,
);
