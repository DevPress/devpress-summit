'use strict';
module.exports = function(grunt) {

	// load all tasks
	require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			files: ['assets/scss/**/*.scss', 'assets/js/**/*.js'],
			tasks: ['sass', 'postcss', 'cssmin', 'concat', 'uglify'],
			options: {
				livereload: true,
			},
		},
		sass: {
			default: {
				options : {
					style : 'expanded',
					sourceMap: true
				},
				files: {
					'style.css':'scss/style.scss',
				}
			}
		},
		postcss: {
			options: {
				map: true,
				processors: [
					require('autoprefixer-core')({browsers: 'last 2 versions'}),
				]
			},
			files: {
				'style.css':'style.css'
			}
		},
		concat: {
			default: {
				src: [
					'js/skip-link-focus-fix.js',
					'js/jquery.fitvids.js',
					'js/theme.js'
				],
				dest: 'js/theme.min.js',
			}
		},
		uglify: {
			default: {
				src: 'js/theme.min.js',
				dest: 'js/theme.min.js'
			}
		},
		replace: {
			styleVersion: {
				src: [
					'scss/style.scss',
				],
				overwrite: true,
				replacements: [{
					from: /^.Version:.*$/m,
					to: 'Version: <%= pkg.version %>'
				}]
			},
			functionsVersion: {
				src: [
					'functions.php'
				],
				overwrite: true,
				replacements: [ {
					from: /^define\( 'SUMMIT_VERSION'.*$/m,
					to: 'define( \'SUMMIT_VERSION\', \'<%= pkg.version %>\' );'
				} ]
			},
		},
		// https://www.npmjs.org/package/grunt-wp-i18n
		makepot: {
			target: {
				options: {
					domainPath: '/languages/',
					potFilename: 'summit.pot',
					potHeaders: {
					poedit: true, // Includes common Poedit headers.
					'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
				},
				type: 'wp-theme',
				updateTimestamp: false,
				processPot: function( pot, options ) {
					pot.headers['report-msgid-bugs-to'] = 'https://devpress.com/';
					pot.headers['language'] = 'en_US';
					return pot;
					}
				}
			}
		},
		cssjanus: {
			theme: {
				options: {
					swapLtrRtlInUrl: false
				},
				files: [
					{
						src: 'style.css',
						dest: 'style-rtl.css'
					}
				]
			}
		}
	});

	grunt.registerTask( 'default', [
		'sass',
		'postcss',
		'concat',
		'uglify'
	]);

	grunt.registerTask( 'release', [
		'replace',
		'sass',
		'postcss',
		'concat',
		'uglify',
		'makepot',
		'cssjanus'
	]);

};
