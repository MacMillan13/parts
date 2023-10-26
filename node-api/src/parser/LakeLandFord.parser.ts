import { AbstractParser } from './abstract.parser';
import axios from 'axios';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../dto/ParsedElement';

export class LakeLandFordParser extends AbstractParser {
  private readonly storeUrl: string = 'https://parts.lakelandford.com';
  private readonly searchUrl: string =
    this.storeUrl + '/productSearch.aspx?searchTerm=';

  async parse(partNumber: string): Promise<Array<any>> {
    const axiosSearchResponse = await axios.get(this.searchUrl + partNumber);

    const $ = cheerio.load(axiosSearchResponse.data);
    const element = $('.productDetails');
    const price = $(element)
      .find('.item-desc')
      .text()
      .replace('\\t', '');
    const text = $(element)
      .find('.product-title-module .product-title')
      .text()
      .replace('\\t', '');
    const shipping = $(element).find('.shippingImg').text().replace('/t', '');

    const link = axiosSearchResponse.request.path;

    const parsedElement = new ParsedElement();

    parsedElement.price = price;
    parsedElement.text = text;
    parsedElement.elementLink = this.storeUrl + link;
    parsedElement.shipping = shipping;

    return [parsedElement];
  }
}
