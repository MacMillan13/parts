import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { StoreParserGateway } from './store-parser.gateway';
import { MongooseModule } from '@nestjs/mongoose';
import { ParserService } from './service/parser.service';
import { RockAutoParser } from './parser/RockAutoParser';
import { PartsGeekParser } from "./parser/PartsGeek.parser";
import { MoparPartsGiantParser } from "./parser/MoparPartsGiant.parser";
import { TurnerMotorSportParser } from "./parser/TurnerMotorSport.parser";

@Module({
  imports: [MongooseModule.forRoot('mongodb://localhost/parts')],
  controllers: [AppController],
  providers: [AppService, StoreParserGateway, ParserService, RockAutoParser,
    PartsGeekParser, MoparPartsGiantParser, TurnerMotorSportParser],
})
export class AppModule {}
