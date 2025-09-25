export default {
  install(app, { messages, locale, userCurrency }) {
    const dict = messages || {}
    const t = (key) => dict[key] ?? key

    const currencySymbol = (c) => ({ JPY: '¥', USD: '$', IDR: 'Rp' }[c] ?? c)
    const currencyFormat = (value, currency = userCurrency || 'JPY') => {
      try {
        return new Intl.NumberFormat(undefined, { style: 'currency', currency }).format(value)
      } catch (e) {
        return `${currencySymbol(currency)} ${Number(value||0).toLocaleString()}`
      }
    }

    app.config.globalProperties.$t = t
    app.config.globalProperties.$currency = currencyFormat
    app.provide('t', t)
    app.provide('currencyFormat', currencyFormat)
  }
}