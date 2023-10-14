import { Injectable } from '@nestjs/common';
import { Observable } from 'rxjs';
import { RockAutoParser } from '../parser/RockAutoParser';
import { TurnerMotorSportParser } from "../parser/TurnerMotorSport.parser";
import { MoparPartsGiantParser } from "../parser/MoparPartsGiant.parser";

@Injectable()
export class ParserService {
  constructor(private readonly rockAutoParser: RockAutoParser, private readonly turnerMotorSport: TurnerMotorSportParser,
              private readonly moparPartsGiant: MoparPartsGiantParser) {}
  startParsing(query: string): Observable<unknown> {
    return new Observable((subscriber) => {
      this.turnerMotorSport.parse(query).then((result) => {
        subscriber.next(result)
      })
      this.moparPartsGiant.parse(query).then(result => {
        subscriber.next(result)
      })
    });
  }
}
