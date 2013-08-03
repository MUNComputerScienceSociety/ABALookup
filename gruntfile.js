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
					'vendor/jquery.min.js',
					'vendor/hsp.jquery.min.js',
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
		}
	};
	// UglifyJS mappings
	gruntConfigOptions.uglify.main.files[dirJs + 'main.min.js'] = dirJs + 'main.js';
	// Sass mappings
	gruntConfigOptions.sass.main.files[dirCss + 'main.css'] = dirSass + 'main.scss';
	gruntConfigOptions.sass.main.files[dirCss + 'main.alt.css'] = dirSass + 'main.alt.scss';
	// Grunt options
	grunt.initConfig(gruntConfigOptions);
	var matchdep = require('matchdep');
	matchdep.filter('grunt-*').forEach(grunt.loadNpmTasks);
	grunt.registerTask('default', 'Does nothing.', function () {
		grunt.log.write('Doing nothing.');
	});
	grunt.registerTask('js', 'Concat and minify JavaScript.', ['concat', 'uglify']);
	grunt.registerTask('lint', ['jshint']);
	grunt.registerTask('hint', ['jshint']);
};
