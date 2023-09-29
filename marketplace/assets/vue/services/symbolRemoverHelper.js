export const removeSymbols = (string) => string.replace(/\s+/g, '_')
    .replace(/\//g, '-')
    .replace(/[`~!@#$%^&*()|+=?;:'",.<>\{\}\[\]\\\/]/gi, '-')
    .toLowerCase()