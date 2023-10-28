// https://www.oemfordpartsdirect.com/search?search_str=1

import { AbstractParser } from './abstract.parser';
import axios from 'axios';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../dto/ParsedElement';

export class OemFordPartsDirectParser extends AbstractParser {
  private readonly storeUrl: string = 'https://www.oemfordpartsdirect.com';
  // TP none
  // l1mz10a313ba
  private readonly searchUrl: string = this.storeUrl + '/search?search_str=';

  async parse(partNumber: string): Promise<Array<any>> {
    const axiosSearchResponse = await axios.get(this.searchUrl + partNumber);

    const $ = cheerio.load(axiosSearchResponse.data);
    const element = $('.page-builder-layout-column-wrap');
    const price = $(element).find('#product_price').text();
    const text = $(element).find('.product-title').text();

    const link = axiosSearchResponse.request.path;

    const parsedElement = new ParsedElement();

    parsedElement.price = price;
    parsedElement.text = text;
    parsedElement.elementLink = this.storeUrl + link;

    return [parsedElement];
  }
}
