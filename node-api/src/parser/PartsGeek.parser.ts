import { Injectable } from "@nestjs/common";
import * as cheerio from "cheerio";
import { ParsedElement } from "../dto/ParsedElement";
import { AbstractParser } from "./abstract.parser";

@Injectable()
export class PartsGeekParser extends AbstractParser {
  private readonly searchUrl: string =
    'https://www.partsgeek.com/ss/?i=1&ssq='
  // Test code 11378662525
  // TP 3.8

  async parse(partNumber: string): Promise<Array<any>> {

    const data = await this.getDom(this.searchUrl, partNumber);

    const $ = cheerio.load(data);

    const elements = [];

    $('.product').each((index, element) => {
      const text = $(element).find('.product-title').text();
      const price = $(element).find('.product-price').text();
      const productAttributesHeading = $(element).toArray()

      console.log(4444, productAttributesHeading);

      const parsedElement = new ParsedElement();

      parsedElement.text = text;
      parsedElement.price = price;

      elements.push(parsedElement);
    })

    return elements;
  }
}
