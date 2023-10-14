import { Injectable } from '@nestjs/common';
import { Observable } from 'rxjs';
import { RockAutoParser } from '../parser/RockAutoParser';

@Injectable()
export class ParserService {
  constructor(private readonly rockAutoParser: RockAutoParser) {}
  startParsing(query: string): Observable<unknown> {
    return new Observable((subscriber) => {
      subscriber.next(this.rockAutoParser.parse(query));
    });
  }
}
