import { AbstractParser } from "./abstract.parser";
import { ParsedElement } from "../dto/ParsedElement";

export class TurnerMotorSportParser extends AbstractParser {

  private readonly url: string = 'https://www.turnermotorsport.com/'
  private readonly searchUrl: string = this.url + 'Search?No=0&Nrpp=50&Ntt='
  // 11128654272

  async parse(partNumber: string): Promise<Array<any>> {

    const axiosResponse = await this.getDom(this.searchUrl, partNumber)

    const elements = []

    axiosResponse.contents[0].data.contents[0].data.mainContent.filter(
      function(item) {
        if ('ContentSlotMain' === item['@type']) {

          item.data.contents.filter(
            function(element) {
              if (undefined !== element['@type'] && 'ResultsList' === element['@type']) {
                element.data.records.forEach(record => {
                  const attributes = record.attributes

                  const parsedElement = new ParsedElement();

                  parsedElement.text = attributes.description[0]
                  parsedElement.price = attributes.priceRetail[0]
                  parsedElement.manufacture = attributes.brandName[0]
                  parsedElement.elementLink = attributes.productPath[0]

                  elements.push(parsedElement)
                })
              }
            }
          )
        }
      }
    )

    elements.forEach(element => {
      element.elementLink = this.url + '/' + element.elementLink
    })

    return elements;
  }
}