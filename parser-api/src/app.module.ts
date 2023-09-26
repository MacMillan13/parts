import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { StoreParserGateway } from './store-parser.gateway';
import { MongooseModule } from '@nestjs/mongoose';

@Module({
  imports: [MongooseModule.forRoot('mongodb://localhost/parts')],
  controllers: [AppController],
  providers: [AppService, StoreParserGateway],
})
export class AppModule {}
