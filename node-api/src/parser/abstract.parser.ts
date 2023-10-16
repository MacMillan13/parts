import axios from 'axios';

export abstract class AbstractParser {
  protected async getDom(
    url: string,
    partNumber: string,
    config: object = null,
  ) {
    if (null === config) {
      config = this.getConfig();
    }

    const axiosResponse = await axios.get(url + partNumber, config);

    return axiosResponse.data;
  }

  protected getConfig() {
    return {
      headers: {
        'Accept-Language': 'en',
        'User-Agent':
          'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
      },
    };
  }
}
