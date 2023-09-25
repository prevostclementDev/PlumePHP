

/*
 * HOW USE GULP.
 *
 * To minify JS, and transform scss -> css.
 * use command >gulp or >gulp default.
 *
 * To reset all minify JS and all scss transform css
 * use command >gulp reset
 *
 * To delete all minify JS and scss transform to css
 * use command >gulp delete
 *
*/


/* ######################## */
/* ###### IMPORTATION ##### */
/* ######################## */
const { gulp, series, parallel, watch, src, dest  } = require('gulp');

const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const babel = require('gulp-babel');

const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');

const del = require('delete');

/* ################## */
/* ###### FILES ##### */
/* ################## */

/* CHEMIN FICHIER */
const chemin = {
    styles : {
        Inspectdev : 'assets/style/scss/**/*.scss',
        dev : 'assets/style/scss/*.scss',
        prod : 'assets/style/css/'
    },
    script: {
        dev : 'assets/js/sources/*.js',
        prod : 'assets/js/'
    },
}

/* ################# */
/* ###### TASK ##### */
/* ################# */

// default style export
const defaultStyle = () => {
    return src( chemin.styles.dev )
        .pipe( sass() )
        .pipe( cleanCSS() )
        .pipe( rename( { suffix : '-min' } ) )
        .pipe( dest( chemin.styles.prod ) );
}

// default js export
const defaultJavaScript = () => {
    return src( chemin.script.dev )
        .pipe( babel() )
        .pipe( uglify() )
        .pipe( rename( {suffix : "-min"} ) )
        .pipe( dest( chemin.script.prod) );
}

// default watcher export
function defaultGulpTaskWatcher(){
    watch( chemin.styles.Inspectdev , defaultStyle );
    watch( chemin.script.dev , defaultJavaScript );
}


exports.default = defaultGulpTaskWatcher;

// delete all file
const deleteAll = (cb) => {
    del([chemin.script.prod+'*.js'],cb)
    del([chemin.styles.prod+'*.css'],cb)
}

// reset export file
exports.reset = (cb) => {
    deleteAll(cb);

    defaultStyle();
    defaultJavaScript();
};

// delete all export file
exports.delete = deleteAll;