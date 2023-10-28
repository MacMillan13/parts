import { Injectable } from '@nestjs/common';
import { AbstractParser } from './abstract.parser';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../dto/ParsedElement';

@Injectable()
export class OneAautoParser extends AbstractParser {
  private readonly storeUrl: string = 'https://www.1aauto.com';
  private readonly searchUrl: string = this.storeUrl + '/search?q=';
  // SPA02152
  // TP 4.2

  async parse(partNumber: string): Promise<Array<any>> {
    const data = await this.getDom(this.searchUrl, partNumber);

    const $ = cheerio.load(data);

    const elements = [];

    $('#serp-list li').each((index, element) => {
      const price = $(element).find('.price-column .price-sale span').text();
      const text = $(element).find('.replaces-title').text();
      const brand = $(element).find('.oos-desc-brand').text();
      const link = $(element).find('.replaces-title').attr('href');

      const parsedElement = new ParsedElement();

      if (price.length !== 0) {
        parsedElement.price = price;
        parsedElement.manufacture = brand;
        parsedElement.text = text;
        parsedElement.elementLink = this.storeUrl + link;

        elements.push(parsedElement);
      }
    });

    return elements;
  }
}
