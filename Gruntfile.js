module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        wiredep: {
            task: {
                ignorePath: '../../../',
                src: ['application/views/partial/header.php']
            }
        },
        wiredep_templates: {
            task: {
                ignorePath: '../../../../',
                src: ['templates/*/views/partial/header.php']
            }
        },
        bower_concat: {
            all: {
                mainFiles: {
                    'bootswatch-dist': ['bootstrap/dist/js/bootstrap.js'],
                },
                dest: {
                    'js': 'tmp/opensourcepos_bower.js',
                    'css': 'tmp/opensourcepos_bower.css'
                }
            }
        },
        bowercopy: {
			options: {
				// Bower components folder will be removed afterwards 
				// clean: true
			},
			targetdist: {
				options: {
					destPrefix: 'dist'
				},
				files: {
					'jquery-ui.css': 'jquery-ui/themes/base/jquery-ui.css',
					'bootstrap.min.css': 'bootswatch-dist/css/bootstrap.min.css'
				}
			}/*,
			targettmp: {
				options: {
					destPrefix: 'tmp'
				},
				files: {
					// add here anything that should be copied in a tmp directory
				}
			}*/
        },
        cssmin: {
            target: {
                files: {
                    'dist/<%= pkg.name %>.min.css': ['tmp/opensourcepos_bower.css', 'css/*.css', '!css/login.css', '!css/invoice_email.css']
                }
            }
        },
        concat: {
            js: {
                options: {
                    separator: ';'
                },
                files: {
                    'dist/<%= pkg.name %>.js': ['tmp/opensourcepos_bower.js', 'js/*.js']
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
                    'dist/<%= pkg.name %>.min.js': ['dist/<%= pkg.name %>.js']
                }
            }
        },
        jshint: {
            files: [ 'Gruntfile.js', 'js/*.js' ],
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
                    absolutePath: true
                },
                src: [ 'css/*.css', '!css/login.css', '!css/invoice_email.css' ],
                dest: 'application/views/partial/header.php',
                dest: 'templates/spacelab/views/partial/header.php'
            },
            mincss_header: {
                options: {
                    scriptTemplate: '<rel type="text/css" src="{{ path }}"></rel>',
                    openTag: '<!-- start mincss template tags -->',
                    closeTag: '<!-- end mincss template tags -->',
                    absolutePath: true
                },
                src: [ 'dist/*.css' ],
                dest: 'application/views/partial/header.php',
            },
            mincss_header_templates: {
                options: {
                    scriptTemplate: '<rel type="text/css" src="{{ path }}"></rel>',
                    openTag: '<!-- start mincss template tags -->',
                    closeTag: '<!-- end mincss template tags -->',
                    absolutePath: true
                },
                src: [ 'dist/*.css', '!dist/bootstrap.min.css' ],
                dest: 'templates/spacelab/views/partial/header.php'
            },
            css_login: {
                options: {
                    scriptTemplate: '<rel type="text/css" src="{{ path }}"></rel>',
                    openTag: '<!-- start css template tags -->',
                    closeTag: '<!-- end css template tags -->',
                    absolutePath: true
                },
                src: [ 'dist/bootstrap.min.css', 'css/login.css' ],
                dest: 'application/views/login.php'
            },
            js: {
                options: {
                    scriptTemplate: '<script type="text/javascript" src="{{ path }}" language="javascript"></script>',
                    openTag: '<!-- start js template tags -->',
                    closeTag: '<!-- end js template tags -->',
                    absolutePath: true
                },
                src: [ 'js/*.js' ],
                dest: 'application/views/partial/header.php',
                dest: 'templates/spacelab/views/partial/header.php'
            },
            minjs: {
                options: {
                    scriptTemplate: '<script type="text/javascript" src="{{ path }}" language="javascript"></script>',
                    openTag: '<!-- start minjs template tags -->',
                    closeTag: '<!-- end minjs template tags -->',
                    absolutePath: true
                },
                src: [ 'dist/*min.js' ],
                dest: 'application/views/partial/header.php',
                dest: 'templates/spacelab/views/partial/header.php'
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
                        'opensourcepos.min.js': 'dist/opensourcepos.min.js',
                        'opensourcepos.min.css': 'dist/opensourcepos.min.css',
                        'bootstrap.min.css': 'dist/bootstrap.min.css'
                    } ],
                    replacement: 'md5'
                },
                files: {
                    src: [ '**/header.php', '**/login.php' ]
                }
            }
        }
    });

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('default', ['wiredep', 'bower_concat', 'bowercopy', 'concat', 'uglify', 'cssmin', 'tags', 'cachebreaker']);

};
