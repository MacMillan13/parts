import { Injectable } from '@nestjs/common';
import axios from 'axios';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../dto/ParsedElement';
import { AbstractParser } from "./abstract.parser";

@Injectable()
export class RockAutoParser extends AbstractParser {
  // Test code 83530-0e010
  private readonly searchUrl: string =
    'https://www.rockauto.com/en/partsearch/?partnum=';
  async parse(partNumber: string): Promise<Array<any>> {

    const data = await this.getDom(this.searchUrl, partNumber)

    const $ = cheerio.load(data);

    const elements = [];

    $('.nobmp tbody').each((index, element) => {

      const text = $(element).find('.listing-text-row').text();

      if (undefined !== text && '' !== text) {
        const price = $(element).find('.listing-price').text();

        const manufacture = $(element)
          .find('.listing-final-manufacturer')
          .text();
        const elementLink = $(element)
          .find('.listing-text-row-moreinfo-truck a')
          .attr('href');

        const parsedElement = new ParsedElement();
        parsedElement.text = text;
        parsedElement.price = price;
        parsedElement.manufacture = manufacture;
        parsedElement.elementLink = elementLink;

        elements.push(parsedElement);
      }
    });

    return elements;
  }
}
