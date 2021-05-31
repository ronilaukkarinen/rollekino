# rollekino
![based_on_air_version 7.6.0_](https://img.shields.io/badge/based_on_air_version-7.6.0_-brightgreen.svg?style=flat-square) ![project_created 31_May_2021](https://img.shields.io/badge/project_created-31_May_2021-blue.svg?style=flat-square) ![Tested_up_to WordPress_5.7.2](https://img.shields.io/badge/Tested_up_to-WordPress_5.7.2-blue.svg?style=flat-square) ![Compatible_with PHP_7.2](https://img.shields.io/badge/Compatible_with-PHP_7.2-green.svg?style=flat-square)

This project is hand made for customer.
## Stack

This project is built on [digitoimistodude/dudestack](https://github.com/digitoimistodude/dudestack), [digitoimistodude/air-light](https://github.com/digitoimistodude/air-light), [digitoimistodude/marlin-vagrant](https://github.com/digitoimistodude/marlin-vagrant) or [digitoimistodude/macos-lemp-setup](https://github.com/digitoimistodude/macos-lemp-setup), [digitoimistodude/devpackages](https://github.com/digitoimistodude/devpackages) which are documented at their corresponding locations. This is a guide on:

- How to setup the project initially<br>
- How to update the project dependencies<br>
- Project settings and features

## Theme screenshot

![Screenshot](/content/themes/rollekino/screenshot.png?raw=true "Screenshot")

## Environments:

Green checkmarks show if the environment is already set up and running, red cross indicates if it's not yet there or disabled.

✅  Development: [rollekino.test](http://rollekino.test)<br>
❌  Staging: [rollekino.vaiheessa.fi](https://rollekino.vaiheessa.fi)<br>
❌  Production: [rollekino.fi](https://rollekino.fi/)

## Setting it up initially

According to our handbook, you should have run `createproject`, inital setup by now. Your Vagrant machine (or macOS LEMP) should be up and running. If not, go back to [dudestack-instructions](https://github.com/digitoimistodude/dudestack-instructions) or [Dude handbook](https://handbook.dude.fi/wordpress-kehitys/projektin-aloitus) and read what you have do.

If dev environment is indeed running, you're ready to version control the project.

There are npm packages in both project root and theme folder. If you come later to this project, please run:

1. `composer install` (in project folder)
2. `npm install` (in project folder)
2. `npm install` (in theme folder)

Run project with `gulp`.
