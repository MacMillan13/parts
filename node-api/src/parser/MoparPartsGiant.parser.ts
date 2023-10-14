import { Injectable } from '@nestjs/common';
import axios from 'axios';
import * as cheerio from 'cheerio';

@Injectable()
export class MoparPartsGiantParser {
  private readonly storeUrl: string = 'https://www.moparpartsgiant.com';
  private readonly searchUrl: string =
    this.storeUrl + '/api/search/search-words?isConflict=false&searchText=';
  async parse(partNumber: string): Promise<{ elements:  Array<any> }> {
    const axiosResponse = await axios.request({
      method: 'GET',
      url: this.searchUrl + partNumber,
      headers: {
        site: 'MPG',
        'User-Agent':
          'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
      },
    });

    console.log(111, axiosResponse.data);

    const param = axiosResponse.data.data.redirectUrl

    if (param.length < 10) {
      return {
        elements: [],
      }
    }

    const redirectUrl = this.storeUrl + param

    const $ = cheerio.load(this.storeUrl + redirectUrl);

    const element = $('.part-number');

    const price = element.find('.price-section-price').text()
    const text = element.find('.pn-detail-main-desc').text()
      + element.find('.pn-detail-sub-desc').text()

    return {
      elements: [{
        price: price,
        text: text,
        link: redirectUrl
      }]
    };
  }
}
