const path                 = require( 'path' )
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' )
const CssMinimizerPlugin   = require( 'css-minimizer-webpack-plugin' )

module.exports = {
	mode: 'production',
	/*
	 * "production" | "development" | "none"
	 * Chosen mode tells webpack to use its built-in optimizations accordingly.
	 */
	entry: './src/index.js',
	/*
	 * defaults to ./src
	 * Here the application starts executing
	 * and webpack starts bundling
	 */
	output: {
		// options related to how webpack emits results
		filename: 'bundle.js',
		path: path.resolve( __dirname, 'public' ),
	},
	module: {
		rules: [
			{
				test: /.s?css$/,
				use: [ MiniCssExtractPlugin.loader, "css-loader", "sass-loader" ],
			},
		],
	},
	optimization: {
		minimize: true,
		minimizer: [
			/*
			 * For webpack@5 you can use the `...` syntax to extend existing minimizers (i.e. `terser-webpack-plugin`), uncomment the next line
			 * `...`,
			 */
			new CssMinimizerPlugin(),
		],
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: "../public/bundle.css",
		} ),
	],
}
