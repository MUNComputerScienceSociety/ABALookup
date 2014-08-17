module.exports = function (grunt) {
	'use strict';

	// Start with nothing
	grunt.initConfig({});

	// Set our directories
	grunt.config('dir', {
		'css': 'public/css',
		'js': 'public/js',
		'sass': 'public/sass'
	});

	// Sass
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.config('sass', {
		'main': {
			'options': {
				'style': 'compressed',
				'precision': 3
			},
			'files': {
				'<%= dir.css %>/main.css': '<%= dir.sass %>/main.scss'
			}
		}
	});

	// Modernizr
	grunt.loadNpmTasks('grunt-modernizr');
	grunt.config('modernizr', {
		'full': {
			'devFile': '<%= dir.js %>/vendor/modernizr/modernizr.js',
			'outputFile': '<%= dir.js %>/modernizr.custom.js',
			'extra': {
				'shiv': true,
				'printshiv': false,
				'load': true,
				'mq': false,
				'cssclasses': true
			},
			'extensibility' : {
				'addtest': false,
				'prefixed': false,
				'teststyles': false,
				'testprops': false,
				'testallprops': false,
				'hasevents': false,
				'prefixes': false,
				'domprefixes': false
			},
			'uglify': false,
			'parseFiles': true,
			'files': {
				'src': [
					'<%= dir.js %>/src/**/*.js'
				]
			}
		}
	});

	// JSHint
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.config('jshint', {
		'options': {
			'ignores': [
				'<%= dir.js %>/**/*.min.js'
			]
		},
		'files': [
			'*.js',
			'<%= dir.js %>/src/**/*.js'
		]
	});

	// Concat
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.config('concat', {
		'options': {
			'separator': ';',
		},
		'dist': {
			'src': [
				'<%= dir.js %>/vendor/jquery/jquery.min.js',
				'<%= dir.js %>/vendor/hideShowPassword/hideShowPassword.min.js',
				'<%= dir.js %>/modernizr.custom.js',
				'<%= dir.js %>/src/forms.js'
			],
			'dest': '<%= dir.js %>/main.js',
		},
	});

	// UglifyJS
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.config('uglify', {
		'main': {
			'files': {
				'<%= dir.js %>/main.min.js': '<%= dir.js %>/main.js'
			}
		}
	});

	// Clean
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.config('clean', {
		'main': [
			'<%= dir.css %>/*.css',
			'<%= dir.js %>/*.js'
		]
	});

	// Shell
	grunt.loadNpmTasks('grunt-shell');
	grunt.config('shell', {
		'test': {
			'command': 'scripts/test'
		},
		'composer': {
			'command': 'composer install'
		}
	});

	// Watch
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.config('watch', {
		'scripts': {
			'files': '<%= dir.sass %>/**/*.scss',
			'tasks': ['sass']
		}
	});

	// Bower
	grunt.registerTask('bower', 'Manage client-side dependencies via Bower', function (command) {
		if (!command) {
			return;
		}

		var done = this.async();
		var bower = require('bower');

		 bower
		.commands
		.install([], {})
		.on('log', function (result) {
			var id = grunt.log.wordlist([result.id], {
				'color': 'cyan'
			});
			grunt.log.writeln('bower ' + id + ' ' + result.message);
		})
		.on('error', function (error) {
			grunt.fail.fatal(error);
		})
		.on('end', done);
	});

	// Task aliases
	grunt.registerTask('compile', 'Compile JS and Sass files', ['clean', 'js', 'sass']);
	grunt.registerTask('install', 'Run Bower and Composer installs', ['bower:install', 'shell:composer']);
	grunt.registerTask('js', 'Concat and minify JavaScript', ['modernizr', 'concat', 'uglify']);
	grunt.registerTask('test', 'Run `scripts/test`', ['shell:test']);
	grunt.registerTask('lint', 'Validate files with JSHint', ['jshint']);

	// Default task
	grunt.registerTask('default', 'Does nothing', function () {
		grunt.log.writeln('Doing nothing');
	});
};
