import { Controller, Get, Param } from '@nestjs/common';
import { AppService } from './app.service';
import { TurnerMotorSportParser } from './parser/TurnerMotorSport.parser';
import { ECSTuningParser } from './parser/ECSTuning.parser';
import { FordPartsGiantParser } from './parser/FordPartsGiant.parser';
import { MoparPartsGiantParser } from './parser/MoparPartsGiant.parser';
import { OemFordPartsDirectParser } from './parser/OemFordPartsDirect.parser';
import { TascaPartsParser } from './parser/TascaParts.parser';
import { LakeLandFordParser } from './parser/LakeLandFord.parser';
import { FordOEMPartsOnlineParser } from './parser/FordOEMPartsOnline.parser';
import { OneAautoParser } from './parser/OneAauto.parser';

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  getHello(): string {
    return this.appService.getHello();
  }

  @Get(':partNumber')
  async getData(@Param('partNumber') partNumber: string) {
    // Actual shipping time will be calculated at checkout.
    const parser = new OneAautoParser();

    return parser.parse(partNumber);
  }
}
