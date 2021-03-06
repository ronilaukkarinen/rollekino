const themeDir = 'content/themes/rollekino/';

module.exports = {
  cleancss: {
    opts: {
      compatibility: '-properties.merging',
      level: {
        1: {
          optimizeFont: false,
          optimizeFontWeight: true,
          optimizeOutline: true,
          specialComments: false,
          removeQuotes: false,
          removeWhitespace: true,
          removeEmpty: true,
          tidyAtRules: true,
          tidyBlockScopes: true,
          tidySelectors: true,
          cleanupCharsets: true,
          replaceMultipleZeros: true,
          selectorsSortingMethod: 'standard'
        },
        2: {
          mergeSemantically: false,
          mergeMedia: false,
          mergeIntoShorthands: true,
          overrideProperties: true,
          removeEmpty: true,
          removeDuplicateRules: true,
          reduceNonAdjacentRules: true,
          removeDuplicateFontRules: true,
          removeDuplicateMediaBlocks: true,
          removeUnusedAtRules: false,
          restructureRules: false,
          urlQuotes: true
        }
      }
    }
  },
  rename: {
    min: {
      suffix: '.min'
    }
  },
  browsersync: {
    // Important! If src is wrong, styles will not inject to the browser
    src: [themeDir + 'css/**/*'],
    opts: {
      logLevel: 'debug',
      injectChanges: true,
      proxy: 'https://rollekino.test',
      browser: 'Google Chrome',
      open: false,
      notify: true,
      // Generate with: mkdir -p /var/www/certs && cd /var/www/certs && mkcert localhost 192.168.x.xxx ::1
      https: {
        key: "C:\\Users\\Rolle\\Projects\\certs\\localhost-key.pem",
        cert: "C:\\Users\\Rolle\\Projects\\certs\\localhost.pem",
      }
    },
  },
  styles: {
    gutenberg: themeDir + 'sass/base/gutenberg.scss',
    src: themeDir + 'sass/*.scss',
    development: themeDir + 'css/dev/',
    production: themeDir + 'css/prod/',
    watch: {
      development: themeDir + 'sass/**/*.scss',
      production: themeDir + 'css/dev/*.css',
    },
    stylelint: {
      src: themeDir + 'sass/**/*.scss',
      opts: {
        fix: false,
        reporters: [{
          formatter: 'string',
          console: true,
          failAfterError: false,
          debug: false
        }]
      },
    },
    opts: {
      development: {
        verbose: true,
        bundleExec: false,
        outputStyle: 'expanded',
        debugInfo: true,
        errLogToConsole: true,
        includePaths: [themeDir + 'node_modules/'],
        quietDeps: true,
      },
      production: {
        verbose: false,
        bundleExec: false,
        outputStyle: 'compressed',
        debugInfo: false,
        errLogToConsole: false,
        includePaths: [themeDir + 'node_modules/'],
        quietDeps: true,
      }
    }
  },
  js: {
    src: themeDir + 'js/src/*.js',
    watch: themeDir + 'js/src/**/*',
    production: themeDir + 'js/prod/',
    development: themeDir + 'js/dev/',
  },
  php: {
    watch: [
      themeDir + '*.php',
      themeDir + 'inc/**/*.php',
      themeDir + 'template-parts/**/*.php'
    ]
  },
  phpcs: {
    src: [themeDir + '**/*.php', '!' + themeDir + 'node_modules/**/*'],
    opts: {
      bin: 'C:\\wsl-tools\\phpcs.bat',
      standard: themeDir + 'phpcs.xml',
      warningSeverity: 0
    }
  }
};
