module.exports = function(grunt) {
    grunt.initConfig({
        pkg : grunt.file.readJSON('package.json'),
          watch: {
            //https://chrome.google.com/webstore/detail/livereload/jnihajbhpnppcggbcgedagnkighmdlei
            //<script type="text/javascript" src="http://127.0.0.1:35729/livereload.js" ></script>
            client: {
                files: ['**/*.html', '**/*.css'],
                options: {
                    livereload: true
                }
            },
            less:{
                files:['**/*.less'],
                tasks:['less:dev']
            },
            script:{
                files:['**/*.js'],
                tasks:['uglify:prod']
            }

        },
        autoprefixer: {
            options: {
                browsers: [
                    //'Android',
                    //'iOS'
                    // 'ExplorerMobile'
                ]//> 1%, last 2 versions, Firefox ESR, Opera 12.1:
            },
            prod:{
                expand: true,
                cwd:'css',
                src: '*.css', 
                // dest: 'css/build,' 
                dest: 'css'
            }
        },
        cssmin: {
            prod: {
              expand: true,
              cwd: 'css',
              src: '*.css', 
              dest: 'css' ,
              ext: '.css'
            }
        },
        less: {
            options: {
                compress:false, 
                yuicompress: false
            },
            dev: {
                options: {
                  dumpLineNumbers:"comments" // or "mediaQuery" or "all"
                },
                files: [{
                    cwd:'css.src',
                    expand: true,
                    src:["*.less"],
                    dest:"css",
                    ext:".css"
                }]
            },
            prod:{
                files: [{
                    cwd:'css.src',
                    expand: true,
                    src:["*.less"],
                    dest:"css",
                    ext:".css"
                }]
            }
        },
        imagemin: {
          prod:{
            files: [{
              expand: true,                  // Enable dynamic expansion
              cwd: 'images',                   // Src matches are relative to this path
              src: ['**/*.{png,jpg}'],   // Actual patterns to match
              dest: 'images'                  // Destination path prefix
            }]
          }
        },
         uglify: {
            options: {
                //banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'//添加banner
            },
            prod: {
              options: {
                mangle: true, //行参替换混淆
                compress: false, //优化去除出错和无效的行参
                beautify: false,//不压缩到一行美观
                report: "gzip"//输出压缩率，可选的值有 false(不输出信息)，gzip
              },
              cwd:'js.src',
              expand:true,
              src:['**/*.js'],
              dest:"js",
              ext:".js"
              // ext:".min.js"
              // files: {
              //   'controllers/index.min.js': ['controllers/src/index.js']
              // }
            }
        },
        clean: {
          dev: ["images.src"]
        },
        copy: {
          dev:{
             files: [
                 // includes files within path
                  {expand: true, cwd:"images",src: ['**/*'], dest: 'images.src', filter: 'isFile'}
              ]
          }
        }
    });

    
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.loadNpmTasks('grunt-contrib-clean');

    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-autoprefixer');

    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.loadNpmTasks('grunt-contrib-imagemin');

    grunt.loadNpmTasks('grunt-contrib-concat');


    grunt.registerTask('default', ['less:dev','autoprefixer:prod']);
//'imagemin:prod'
    grunt.registerTask('release', ['uglify:prod','less:prod','autoprefixer:prod','cssmin:prod']);

    grunt.registerTask('release_js', ['uglify:prod']);

    grunt.registerTask('release_css', ['less:prod','autoprefixer:prod','cssmin:prod']);

    grunt.registerTask('build_img', ['copy:dev','imagemin:dev']);

    grunt.registerTask('build_less', ['less:dev']);

    grunt.registerTask('build_watch', ['watch:less']);
    grunt.registerTask('build_watch2', ['watch:script']);

    grunt.registerTask('build_live_reload', ['watch:client']);


};
 