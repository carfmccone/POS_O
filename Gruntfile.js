module.exports = function(grunt) {

    grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		wiredep: {
			task: {
				ignorePath: '../../../public/',
				src: ['application/views/partial/header.php']
			}
		},
		bower_concat: {
			all: {
				mainFiles: {
					'bootstrap-table': [ "src/bootstrap-table.js", "src/bootstrap-table.css", "dist/extensions/export/bootstrap-table-export.js", "dist/extensions/mobile/bootstrap-table-mobile.js"]
				},
				dest: {
					'js': 'tmp/opensourcepos_bower.js',
					'css': 'tmp/opensourcepos_bower.css'
				}
			}
		},
		bowercopy: {
			options: {
				report: false
			},
			targetcssdist: {
				options: {
					srcPrefix: 'css',
					destPrefix: 'public/dist'
				},
				files: {
					'login.css': 'login.css',
					'style.css': 'style.css',
					'invoice_email.css': 'invoice_email.css',
					'barcode_font.css': 'barcode_font.css'
				}
			},
			targetdist: {
				options: {
					destPrefix: 'public/dist'
				},
				files: {
					'login.css': '../../css/login.css',
					'style.css': '../../css/style.css',
					'invoice_email.css': '../../css/invoice_email.css',
					'barcode_font.css': '../../css/barcode_font.css',
					'jquery-ui.css': 'jquery-ui/themes/base/jquery-ui.css',
				}
			},
			targetdistbootswatch: {
				options: {
					srcPrefix: 'public/bower_components/bootswatch',
					destPrefix: 'public/dist/bootswatch'
				},
				files: {
					'cerulean/bootstrap.min.css': 'cerulean/bootstrap.min.css',
					'cosmo/bootstrap.min.css': 'cosmo/bootstrap.min.css',
					'cyborg/bootstrap.min.css': 'cyborg/bootstrap.min.css',
					'darkly/bootstrap.min.css': 'darkly/bootstrap.min.css',
					'flatly/bootstrap.min.css': 'flatly/bootstrap.min.css',
					'journal/bootstrap.min.css': 'journal/bootstrap.min.css',
					'paper/bootstrap.min.css': 'paper/bootstrap.min.css',
					'readable/bootstrap.min.css': 'readable/bootstrap.min.css',
					'sandstone/bootstrap.min.css': 'sandstone/bootstrap.min.css',
					'slate/bootstrap.min.css': 'slate/bootstrap.min.css',
					'spacelab/bootstrap.min.css': 'spacelab/bootstrap.min.css',
					'superhero/bootstrap.min.css': 'superhero/bootstrap.min.css',
					'united/bootstrap.min.css': 'united/bootstrap.min.css',
					'yeti/bootstrap.min.css': 'yeti/bootstrap.min.css',
					'fonts': 'fonts'
				}
			},
			targetlicense: {
				options: {
					srcPrefix: './'
				},
				files: {
					'public/license': 'LICENSE'
				}
			},
		},
		cssmin: {
			target: {
				files: {
					'public/dist/<%= pkg.name %>.min.css': ['tmp/opensourcepos_bower.css', 'css/*.css', '!css/login.css', '!css/invoice_email.css', '!css/barcode_font.css', '!css/style.css']
				}
			}
		},
		concat: {
			js: {
				options: {
					separator: ';'
				},
				files: {
					'tmp/<%= pkg.name %>.js': ['tmp/opensourcepos_bower.js', 'js/jquery*', 'js/*.js']
				}
			},
			sql: {
				options: {
					banner: '-- >> This file is autogenerated from tables.sql and constraints.sql. Do not modify directly << --'
				},
				files: {
					'database/database.sql': ['database/tables.sql', 'database/constraints.sql'],
					'database/migrate_phppos_dist.sql': ['database/tables.sql', 'database/phppos_migrate.sql', 'database/constraints.sql']
				}
			}
		},
		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
			},
			dist: {
				files: {
					'public/dist/<%= pkg.name %>.min.js': ['tmp/<%= pkg.name %>.js']
				}
			}
		},
		jshint: {
			files: ['Gruntfile.js', 'js/*.js'],
			options: {
				// options here to override JSHint defaults
				globals: {
					jQuery: true,
					console: true,
					module: true,
					document: true
				}
			}
		},
		tags: {
			css_header: {
				options: {
					scriptTemplate: '<rel type="text/css" src="{{ path }}"></rel>',
					openTag: '<!-- start css template tags -->',
					closeTag: '<!-- end css template tags -->',
					ignorePath: '../../../'
				},
				src: ['css/*.css', '!css/login.css', '!css/invoice_email.css', '!css/barcode_font.css'],
				dest: 'application/views/partial/header.php',
			},
			mincss_header: {
				options: {
					scriptTemplate: '<rel type="text/css" src="{{ path }}"></rel>',
					openTag: '<!-- start mincss template tags -->',
					closeTag: '<!-- end mincss template tags -->',
					ignorePath: '../../../public/'
				},
				src: ['public/dist/*.css', '!public/dist/login.css', '!public/dist/invoice_email.css', '!public/dist/barcode_font.css'],
				dest: 'application/views/partial/header.php',
			},
			css_login: {
				options: {
					scriptTemplate: '<rel type="text/css" src="{{ path }}"></rel>',
					openTag: '<!-- start css template tags -->',
					closeTag: '<!-- end css template tags -->',
                    ignorePath: '../../public/'
				},
				src: ['public/dist/login.css'],
				dest: 'application/views/login.php'
			},
			js: {
				options: {
					scriptTemplate: '<script type="text/javascript" src="{{ path }}"></script>',
					openTag: '<!-- start js template tags -->',
					closeTag: '<!-- end js template tags -->',
					ignorePath: '../../../' 
				},
				src: ['js/jquery*', 'js/*.js'],
				dest: 'application/views/partial/header.php'
			},
			minjs: {
				options: {
					scriptTemplate: '<script type="text/javascript" src="{{ path }}"></script>',
					openTag: '<!-- start minjs template tags -->',
					closeTag: '<!-- end minjs template tags -->',
                    ignorePath: '../../../public/'
				},
				src: ['public/dist/*min.js'],
				dest: 'application/views/partial/header.php'
			}
		},
		mochaWebdriver: {
			options: {
				timeout: 1000 * 60 * 3
			},
			test : {
				options: {
					usePhantom: true,
					usePromises: true
				},
				src: ['test/**/*.js']
			}
		},
		watch: {
			files: ['<%= jshint.files %>'],
			tasks: ['jshint']
		},
		cachebreaker: {
			dev: {
				options: {
					match: [ {
						'opensourcepos.min.js': 'public/dist/opensourcepos.min.js',
						'opensourcepos.min.css': 'public/dist/opensourcepos.min.css'
					} ],
					replacement: 'md5'
				},
				files: {
					src: ['**/header.php', '**/login.php']
				}
			}
		},
		clean: {
			license: ['public/bower_components/**/bower.json']
		},
		license: {
			all: {
				// Target-specific file lists and/or options go here. 
				options: {
					// Target-specific options go here. 
					directory: 'public/bower_components',
					output: 'public/license/bower.LICENSES'
				},
			},
		},
		'bower-licensechecker': {
			options: {
				/*directory: 'path/to/bower',*/
				acceptable: [ 'MIT', 'BSD', 'LICENSE.md' ],
				printTotal: true,
				warn: {
					nonBower: true,
					noLicense: true,
					allGood: true,
					noGood: true
				},
				log: {
					outFile: 'public/license/.licenses',
					nonBower: true,
					noLicense: true,
					allGood: true,
					noGood: true,
				}
			}
		}
    });

    require('load-grunt-tasks')(grunt);
    grunt.loadNpmTasks('grunt-mocha-webdriver');

    grunt.registerTask('default', ['wiredep', 'bower_concat', 'bowercopy', 'concat', 'uglify', 'cssmin', 'tags', 'cachebreaker']);
    grunt.registerTask('genlicense', ['clean:license', 'license', 'bower-licensechecker']);

};
