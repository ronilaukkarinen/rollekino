export default function getLocalization(stringKey) {
  if (typeof window.rollekino_screenReaderText === 'undefined' || typeof window.rollekino_screenReaderText[stringKey] === 'undefined') {
    // eslint-disable-next-line no-console
    console.error(`Missing translation for ${stringKey}`);
    return '';
  }
  return window.rollekino_screenReaderText[stringKey];
}
