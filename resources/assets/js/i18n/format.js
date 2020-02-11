// Taken from source: https://github.com/kazupon/vue-i18n/blob/dev/src/format.js
// This allows us to add custom formatters while preserving the actual ones,
// until vue-i18n doesn't overwrite them when using custom formatters.

import _ from 'lodash';

export default class CustomFormatter {
  static _caches = {};

  constructor () {
    this._caches = Object.create(null);
  }

  interpolate (message, values) {
    if (!values) {
      return [message];
    }
    let tokens = this._caches[message];
    if (!tokens) {
      tokens = parse(message);
      this._caches[message] = tokens;
    }
    return compile(tokens, values);
  }
};

const RE_TOKEN_LIST_VALUE = /^(?:\d)+/;
const RE_TOKEN_NAMED_VALUE = /^(?:\w)+/;

export function parse(format) {
  const tokens = [];
  let position = 0;

  let text = '';
  while (position < format.length) {
    let char = format[position++];
    if (char === '{') {
      if (text) {
        tokens.push({ type: 'text', value: text });
      }

      text = '';
      let sub = '';
      char = format[position++];
      while (char !== undefined && char !== '}') {
        sub += char;
        char = format[position++];
      }
      const isClosed = char === '}';

      const type = RE_TOKEN_LIST_VALUE.test(sub)
        ? 'list'
        : isClosed && RE_TOKEN_NAMED_VALUE.test(sub)
          ? 'named'
          : 'unknown';
      tokens.push({ value: sub, type });
    } else if (char === '%') {
      // when found rails i18n syntax, skip text capture
      if (format[(position)] !== '{') {
        text += char;
      }
    } else if (char === ':') {
      // @note: Custom formatting added in order to be able to use Laravel's named formatting.
      // @note: works only for named value
      if (text) {
        tokens.push({ type: 'text', value: text });
      }

      text = '';
      let sub = '';
      while (format[position] !== undefined && RE_TOKEN_NAMED_VALUE.test(format[position])) {
        char = format[position];
        sub += char;
        position++;
      }

      tokens.push({ value: sub, type: 'named' });
    } else {
      text += char;
    }
  }

  text && tokens.push({ type: 'text', value: text });

  return tokens;
};

export function compile(tokens, values) {
  const compiled = [];
  let index = 0;

  const mode = Array.isArray(values)
    ? 'list'
    : _.isObject(values)
      ? 'named'
      : 'unknown';
  if (mode === 'unknown') { return compiled; }

  while (index < tokens.length) {
    const token = tokens[index];
    switch (token.type) {
      case 'text':
        compiled.push(token.value);
        break;
      case 'list':
        compiled.push(values[parseInt(token.value, 10)]);
        break;
      case 'named':
        if (mode === 'named') {
          compiled.push((values)[token.value]);
        } else {
          if (process.env.NODE_ENV !== 'production') {
            console.warn(`[vue-i18n] Type of token '${token.type}' and format of value '${mode}' don't match!`);
          }
        }
        break;
      case 'unknown':
        if (process.env.NODE_ENV !== 'production') {
          console.warn(`[vue-i18n] Detect 'unknown' type of token!`);
        }
        break;
    }
    index++;
  }

  return compiled;
};
