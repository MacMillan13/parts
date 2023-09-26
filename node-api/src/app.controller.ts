import { Controller, Get, Param } from '@nestjs/common';
import { AppService } from './app.service';
import { RockAutoParser } from './Parser/RockAutoParser';

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  getHello(): string {
    return this.appService.getHello();
  }

  @Get(':partNumber')
  async getData(@Param('partNumber') partNumber: string): Promise<Array<any>> {
    const rockAutoParser = new RockAutoParser();
    return rockAutoParser.parse(partNumber);
  }
}
