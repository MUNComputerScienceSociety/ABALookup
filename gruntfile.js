module.exports = function (grunt) {
	'use strict';
	var dirCss  = 'public/css/';
	var dirJs   = 'public/js/';
	var dirSass = 'public/sass/';
	var gruntConfigOptions = {
		sass: {
			main: {
				options: {
					style: 'compressed',
					precision: 3
				},
				files: {
					// See below for Sass mappings
				}
			}
		},
		modernizr: {
			devFile: dirJs + 'vendor/modernizr/modernizr.js',
			outputFile: dirJs + 'modernizr.custom.js',
			extra: {
				shiv: true,
				printshiv: false,
				load: true,
				mq: false,
				cssclasses: true
			},
			extensibility : {
				addtest: false,
				prefixed: false,
				teststyles: false,
				testprops: false,
				testallprops: false,
				hasevents: false,
				prefixes: false,
				domprefixes: false
			},
			uglify: false,
			tests: [],
			parseFiles: true,
			files: [
				dirJs + 'src/**/*.js'
			],
			matchCommunityTests: false,
			customTests: []
		},
		jshint: {
			options: {
				ignores: [
					dirJs + '**/*.min.js'
				]
			},
			files: [
				dirJs + 'src/**/*.js'
			]
		},
		concat: {
			options: {
				separator: ';',
			},
			dist: {
				src: [
					'vendor/jquery/jquery.min.js',
					'vendor/hideShowPassword/hideShowPassword.min.js',
					'modernizr.custom.js',
					'src/forms.js'
				].map(function (f) { return dirJs + f; }),
				dest: dirJs + 'main.js',
			},
		},
		uglify: {
			main: {
				files: {
					// See below for UglifyJS mappings
				}
			}
		},
		clean: {
			main: [
				dirCss + '*.css',
				dirJs + '*.js'
			]
		},
		watch: {
			scripts: {
				files: dirSass + '**/*.scss',
				tasks: ['sass']
			}
		}
	};
	// UglifyJS mappings
	gruntConfigOptions.uglify.main.files[dirJs + 'main.min.js'] = dirJs + 'main.js';
	// Sass mappings
	gruntConfigOptions.sass.main.files[dirCss + 'main.css'] = dirSass + 'main.scss';
	// Grunt options
	grunt.initConfig(gruntConfigOptions);
	var matchdep = require('matchdep');
	matchdep.filter('grunt-*').forEach(grunt.loadNpmTasks);
	grunt.registerTask('default', 'Does nothing.', function () {
		grunt.log.write('Doing nothing.');
	});
	grunt.registerTask('js', 'Concat and minify JavaScript.', ['modernizr', 'concat', 'uglify']);
	grunt.registerTask('build', 'The whole enchilada.', ['clean', 'js', 'sass']);
	grunt.registerTask('lint', ['jshint']);
	grunt.registerTask('hint', ['jshint']);
};
