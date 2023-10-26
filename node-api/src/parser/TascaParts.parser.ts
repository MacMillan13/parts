import { AbstractParser } from './abstract.parser';
import { Injectable } from '@nestjs/common';
import axios from 'axios';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../dto/ParsedElement';

@Injectable()
export class TascaPartsParser extends AbstractParser {
  private readonly storeUrl: string = 'https://www.tascaparts.com/';
  private readonly searchUrl: string = this.storeUrl + '/search?search_str=';

  async parse(partNumber: string): Promise<Array<any>> {
    const axiosSearchResponse = await axios.get(this.searchUrl + partNumber);

    const $ = cheerio.load(axiosSearchResponse.data);
    const element = $('.page-builder-layout-column-wrap');
    const price = $(element).find('#product_price').text();
    const text = $(element).find('.product-title').text();
    const manufacture = $(element).find('.manufacturer .list-value').text();

    const link = axiosSearchResponse.request.path;

    const parsedElement = new ParsedElement();

    parsedElement.price = price;
    parsedElement.text = text;
    parsedElement.elementLink = this.storeUrl + link;
    parsedElement.manufacture = manufacture.trim();

    return [parsedElement];
  }
}
