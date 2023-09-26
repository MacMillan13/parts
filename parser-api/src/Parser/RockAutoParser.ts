import { Injectable } from '@nestjs/common';
import axios from 'axios';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../DTO/ParsedElement';

@Injectable()
export class RockAutoParser {
  private readonly searchUrl: string =
    'https://www.rockauto.com/en/partsearch/?partnum=';
  async parse(partNumber: string): Promise<Array<any>> {
    const axiosResponse = await axios.request({
      method: 'GET',
      url: this.searchUrl + partNumber,
      headers: {
        'User-Agent':
          'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
      },
    });

    const $ = cheerio.load(axiosResponse.data);
    const elements = [];

    $('tbody').each((index, element) => {
      const text = $(element).find('.listing-text-row').text();

      if (undefined !== text) {
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
