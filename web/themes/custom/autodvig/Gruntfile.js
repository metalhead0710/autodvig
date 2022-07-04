module.exports = function(grunt) {
  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: [
          {
            expand: true,
            cwd: 'assets/less/',
            src: ['all.less'],
            dest: 'assets/css/',
            ext: '.css'
          }
        ]
      }
    },
    concat: {
      options: {
      },
      dist: {
        src: [
          'assets/css/*.css',
        ],
        dest: 'assets/css/all.min.css'
      }
    },
    watch: {
      styles: {
        files: ['assets/less/**/*.less'],
        tasks: ['compile'],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-newer');

  grunt.registerTask('compile', ['less']);
};
