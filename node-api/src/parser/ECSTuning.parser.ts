import { Injectable } from "@nestjs/common";
import { AbstractParser } from "./abstract.parser";
import * as cheerio from "cheerio";
import { ParsedElement } from "../dto/ParsedElement";

@Injectable()
export class ECSTuningParser extends AbstractParser {
  private readonly storeUrl: string = 'https://www.ecstuning.com'
  private readonly searchUrl: string = this.storeUrl + '/Search/SiteSearch/'
  // 11128654272

  async parse(partNumber: string): Promise<Array<any>> {
    const data = await this.getDom(this.searchUrl, partNumber);

    const $ = cheerio.load(data);

    const elements = [];

    $('.productListBox').each((index, element) => {
      const text = $(element).find('.cleanDesc2').text();
      const price = $(element).find('.price').text();
      const brand = $(element).find('a#brandLink').attr('title');
      const link = $(element).find('.listingThumbWrap').attr('href');

      const parsedElement = new ParsedElement();

      parsedElement.text = text;
      parsedElement.price = price;
      parsedElement.manufacture = brand;
      parsedElement.elementLink = this.storeUrl + link;

      elements.push(parsedElement)
    })

    return elements;
  }
}