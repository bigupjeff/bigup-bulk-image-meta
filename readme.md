# Bigup Web: Plugin Bootstrap


WARNING: This is just code thrown in class-meta-update.php and is not a useable plugin. To be completed.


## Setup


```

I recommend installing phpcs and ESLint VS Code plugins. You can then set VS Code to format on save and highlight errors in realtime.


### Composer Global VS Code Setup (optional)

**Install phpcs system-wide**
`composer global require squizlabs/php_codesniffer`

**Install phpcs VS Code Plugin**
1. Open Visual Studio Code.
2. Press Ctrl+P on Windows or Cmd+P on Mac to open the Quick Open dialog.
3. Type ext install phpcs to find the extension.
4. Press Enter or click the cloud icon to install it.
5. Restart Visual Studio Code when prompted.

**note**: If you see an error with phpcs not being found, you may need to set the path for the phpcs executable manually in the plugin settings. You can either configure this to point to the absolute path of the global phpcs binary, or the relative path of the local phpcs binary in the project.

See [here](https://marketplace.visualstudio.com/items?itemName=ikappas.phpcs) for more instructions.


### ESLint Format on Save (optional)

**Install ESLint VS Code Plugin**
1. Open Visual Studio Code.
2. Press Ctrl+P on Windows or Cmd+P on Mac to open the Quick Open dialog.
3. Type ext install dbaeumer.vscode-eslint to find the extension.
4. Press Enter or click the cloud icon to install it.
5. Restart Visual Studio Code when prompted.

Add this config to your `settings.json` to enable format on save:
```
"editor.defaultFormatter": "dbaeumer.vscode-eslint",
"editor.codeActionsOnSave": {
	"source.fixAll.eslint": true
},
```

See [here](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint) for more instructions.


## Linting

Full linting for CSS/SASS, JS/TS, PHP, markdown and more.

This project uses **npm** package [@wordpress/scripts](https://www.npmjs.com/package/@wordpress/scripts) to lint CSS, JS and markdown files, plus some other helpful tasks.

To lint and fix PHP, phpcs is used along with **composer** package [wp-coding-standards/wpcs](https://packagist.org/packages/wp-coding-standards/wpcs) to enforce WordPress code style.

See package.json and composer.json for npm and composer scripts respectiveley and/or see the package links above for further instructions.

To run a script:

`npm run [script name]`

You can override the preconfigured script code styles with your own config by placing a config e.g. `eslintrc.json` in the root. Your config will be merged with the default settings.


## Distribution

**Note** This bundle setup is a work in progress!
*To-do: create separate bundles for admin/front-end*

This project is setup to make it easy to go from raw code to bundled, minified assets in a distributable zip. To achieve this Webpack is configured independantly of the WordPress scripts.

`npm run build` Runs Webpack using the config in the root. It bundles and minifies JS and CSS from src and outputs bundle.js and bundle.css in `/public`. These bundles are then enqueued in the PHP Initialisation class.

`npm run zip` uses [zip-webpack-plugin](https://www.npmjs.com/package/zip-webpack-plugin)


## License

This project is licensed under the MIT License.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
