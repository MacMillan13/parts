import { AbstractParser } from './abstract.parser';
import axios from 'axios';
import * as cheerio from 'cheerio';
import { ParsedElement } from '../dto/ParsedElement';

export class FordPartsGiantParser extends AbstractParser {
  private readonly storeUrl: string = 'https://www.fordpartsgiant.com';

  private readonly searchUrl: string =
    this.storeUrl + '/api/search/search-words?isConflict=false&searchText=';
  // FL3Z-1620125-B

  async parse(partNumber: string): Promise<Array<any>> {
    const config = this.getConfig();

    config.headers['site'] = 'FPG';

    const axiosSearchResponse = await axios.get(
      this.searchUrl + partNumber,
      config,
    );

    const param = axiosSearchResponse.data.data.redirectUrl;

    if (param.length < 10) {
      return [];
    }

    const axiosResponse = await axios.get(this.storeUrl + param, config);

    const $ = cheerio.load(axiosResponse.data);

    const element = $('.part-number-wrap');
    const price = $(element).find('.price-section-price').text();
    const text =
      $(element).find('.pn-detail-main-desc').text() +
      $(element).find('.pn-detail-sub-desc').text();

    const parsedElement = new ParsedElement();

    parsedElement.price = price;
    parsedElement.text = text;
    parsedElement.elementLink = this.storeUrl + param;

    return [parsedElement];
  }
}
