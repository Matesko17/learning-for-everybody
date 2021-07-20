
var browserSyncState = false;

var gulp = require('gulp');
var del = require('del');
var fs = require('fs');
var merge = require('merge-stream');
var buffer = require('vinyl-buffer');
var gulpSequence = require('gulp-sequence');
var sass = require('gulp-sass');
var pixrem = require('gulp-pixrem');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var cssnano = require('gulp-cssnano');
var postcss = require('gulp-postcss');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var gutil = require('gulp-util');
var imagemin = require('gulp-imagemin');
var replace = require('gulp-replace');
var spritesmith = require('gulp.spritesmith');
const minify = require('gulp-minify');
if (browserSyncState) {
    var browserSync = require('browser-sync');
}

// prevent proti "Possible EventEmitter memory leak detected."
require('events').EventEmitter.defaultMaxListeners = Infinity;

// init env promenne
var env = "";

// nastaveni cest
var paths = {
    base: "./",
    // nepouzivej na zacatek watch cesty ./
    watch: {
        js:         "assets/js/**/*.js",
        sass:       "assets/scss/**/*.scss",
        images:     "assets/images/**/*",
        sprites:    "assets/sprites/*.png",
        files:      "app/presenters/**/*"
    },
    fonts: {
        src: [
                "node_modules/lightgallery/dist/fonts/**",
                "node_modules/slick-carousel/slick/fonts/**",
                "node_modules/@fortawesome/fontawesome-free/webfonts/**"
        ],
        dest:   "www/fonts",
        clean:  "www/fonts/**/*"
    },
    scripts: {
        src: [  
            // jQUERY
            "node_modules/jquery/dist/jquery.js",
            "node_modules/jquery-ui/ui/effect.js",

            // NETTE FORMS
            //"node_modules/nette.ajax.js/nette.ajax.js",
            //"node_modules/live-form-validation/live-form-validation.js",

            // BOOTSTRAP FUNCTIONS
            "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
            
            // CUSTOM LIGHTBOX ALERTS
            // "node_modules/sweetalert2/dist/sweetalert2.js",

            // ES6 PROMISE
            //"node_modules/es6-promise/dist/es6-promise.js",

            // SLICK CAROUSEL
            //"node_modules/slick-carousel/slick/slick.js",

            // FONTAWESOME
            //"node_modules/@fortawesome/fontawesome-free/js/all.js",

            // LIGHT GALLERY
            //"node_modules/lightgallery/dist/js/lightgallery.js",
            // LIGHT GALLERY MODULES
            //"node_modules/lg-autoplay/dist/lg-autoplay.js",
            //"node_modules/lg-fullscreen/dist/lg-fullscreen.js",
            //"node_modules/lg-hash/dist/lg-hash.js",
            //"node_modules/lg-pager/dist/lg-pager.js",
            //"node_modules/lg-share/dist/lg-share.js",
            //"node_modules/lg-thumbnail/dist/lg-thumbnail.js",
            //"node_modules/lg-video/dist/lg-video.js",
            //"node_modules/lg-zoom/dist/lg-zoom.js",

            // AOS
            //"node_modules/aos/dist/aos.js",

            // MASONRY LIBRARY
            //"node_modules/masonry-layout/dist/masonry.pkgd.js",

            // CUSTOM SCROLLBAR PLUGIN
            //"node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.js",

            // OUR CUSTOM SCRIPTS
            "assets/js/**/*.js"
        ],
        dest:   "www/js",
        name:   "scripts.js",
        min:    "scripts.js"
    },
    sass: {
        src:        "assets/scss/styles.scss",
        dest:       "www/css",
        min:        "styles.min.css",
        hotfixes:   "assets/scss/components/hotfixes.scss"
    },
    images: {
        src:    "assets/images/**/*",
        dest:   "www/images",
        clean:  "www/images/**/*"
    },
    sprites: {
        src:        "assets/sprites/*.png",
        url:        "../sprites/sprites.png",
        images: {
            name:   "sprites.png",
            dest:   "www/sprites"
        },
        styles: {
            name:   "sprites.scss",
            dest:   "assets/sprites"
        }
    },
    // fancybox: {
    //     images: {
    //         src: [
    //                 "bower_components/fancybox/source/**/*.gif",
    //                 "bower_components/fancybox/source/**/*.png"
    //         ],
    //         dest:   "www/images/fancybox"
    //     },
    //     preprocess: {
    //         src:    "bower_components/fancybox/source/jquery.fancybox.css",
    //         dest:   "assets/preprocess/_fancybox-preprocess.css"
    //     }
    // },
    slick: {
         images: {
             src:    "node_modules/slick-carousel/slick/*.gif",
             dest:   "www/images/slick"
         }
     },
    lightgallery: {
        images: {
            src:    "node_modules/lightgallery/src/img/**/*",
            dest:   "www/images/lightgallery"
        }
    },
    cleanPreprocessStyles: "assets/preprocess/**/*",
    cleanFilesInProduction: [
        "www/js/scripts.js",
        "www/js/scripts.js.map",
        "www/js/scripts-min.js",
        "www/css/styles.css",
        "www/css/styles.css.map"
    ],
    htaccess: {
        src: {
            dev:        ".htaccess-dev",
            stage:      ".htaccess-stage",
            production: ".htaccess-production"
        },
        dest:           ".htaccess"
    },
    environment: {
        dev:    "app/.development",
        stage:  "app/.stage"
    }
};


// nastaveni tasku pro zpracovavani

var browserSyncOptions = {
    reloadOnRestart: true,
    logSnippet: false,
    scrollRestoreTechnique: 'cookie',
    injectChanges: false,
    minify: false,
    // logLevel: "silent",
    notify: {
        styles: {
            background: '#2d2d2d',
            padding: "10px 15px",
            top: '15px',
            right: '15px',
            color: '#ccc',
            fontSize: '12px',
            borderRadius: "5px",
            fontFamily: "monospace",
        }
    },

    // lze povolit/zakazat jednotlive synchronizace
    // ghostMode: {
    //     clicks: false,
    //     forms: false,
    //     scroll: false
    // },

};

var autoprefixerOptions = {
    browsers: [
        "Android 2.3",
        "Android >= 4",
        "Chrome >= 20",
        "Firefox >= 24",
        "Explorer >= 8",
        "iOS >= 6",
        "Opera >= 12",
        "Safari >= 6"
    ]
};

var spritesmithOptions = {
    imgName: paths.sprites.images.name,
    cssName: paths.sprites.styles.name,
    imgPath: paths.sprites.url,
    // padding: 10,
    // algorithm: "left-right",
    // algorithmOpts: { sort: false },
    // imgOpts: { quality: 75 }
};

var sassOptions = {
    // Default: nested
    // Values: nested, expanded, compact, compressed
    // outputStyle: 'compressed',

    // Default: 5
    // bootstrap-sass requires minimum Sass number precision of 8
    precision: 10
};

var pixremOptions = {
    rootValue: '16px',
    atrules: true,
    unitPrecision: 4
}

var cssnanoOptions = {
    // http://cssnano.co/optimisations/
    // http://cssnano.co/options/
    // discardComments: { removeAll: true },
    calc: false,
    minifySelectors: false,
    minifyFontValues: false,
    convertValues: false,
    autoprefixer: false,
    reduceTransforms: false,
    colormin: false,
    minifyGradients: false,
    discardUnused: false,
    zindex: false,
    mergeIdents: false,
    reduceIdents: false
};

var uglifyStageOptions = {
    mangle: false,
    output: {
        beautify: true
    }
};

var uglifyProductionOptions = {
    mangle: true
};


// tasky pro obsluhu

gulp.task('setup', gulpSequence(
    'set-env-dev',
    'process-scripts',
    'process-sprites',
    'preprocess-styles',
    'process-sass',
    'clean-images',
    'process-images',
    'process-fonts',
    'check-hotfixes',
    'settings-environment'
));

if (browserSyncState) {
    gulp.task('default', gulpSequence(
        'set-env-dev',
        'process-scripts',
        'process-sprites',
        'preprocess-styles',
        'process-sass',
        'clean-images',
        'process-images',
        'process-fonts',
        'check-hotfixes',
        'settings-environment',
        'browsersync-init',
        'watch'
    ));
} else {
    gulp.task('default', gulpSequence(
        'set-env-dev',
        'process-scripts',
        'process-sprites',
        'preprocess-styles',
        'process-sass',
        'clean-images',
        'process-images',
        'process-fonts',
        'check-hotfixes',
        'settings-environment',
        'watch'
    ));
}
gulp.task('stage', gulpSequence(
    'set-env-stage',
    'process-scripts',
    'process-sprites',
    'preprocess-styles',
    'process-sass',
    'clean-images',
    'process-images',
    'process-fonts',
    'check-hotfixes',
    'settings-environment'
));
gulp.task('production', gulpSequence(
    'set-env-production',
    'process-scripts',
    'process-sprites',
    'preprocess-styles',
    'process-sass',
    'clean-images',
    'process-images',
    'process-fonts',
    'check-hotfixes',
    'settings-environment',
    'delete-useless-files'
));


// tasky pro zpracovavani

gulp.task('browsersync-init', function () {
    browserSync.init(browserSyncOptions);
});

gulp.task('bs-reload', function () {
    if (browserSyncState) {
        browserSync.reload();
    } else {
        return gutil.log(gutil.colors.bgBlack("BrowserSync je vypnut!"));
    }
});

gulp.task('check-hotfixes', function () {
    return gulp.src(paths.sass.hotfixes).on('data', function (file) {
        if (file.contents.length > 0) {
            gutil.log(gutil.colors.red("hotfixes.scss obsahuje styly, ktere jsou potreba zanest do struktury!"/* + file.contents*/));
        }
    });
});

gulp.task('delete-useless-files', function () {
    return del(paths.cleanFilesInProduction);
});

gulp.task('settings-environment', gulpSequence('settings-environment-phase1', 'settings-environment-phase2', 'settings-environment-phase3'));
gulp.task('settings-environment-phase1', function () {
    return del([paths.environment.dev, paths.environment.stage]);
});
gulp.task('settings-environment-phase2', function () {
    switch (env) {
        case "dev":
            fs.writeFile(paths.environment.dev, '', function (err) {
                if (err) throw err;
              });
            break;
        case "stage":
            fs.writeFile(paths.environment.stage, '', function (err) {
                if (err) throw err;
              });
            break;
        case "production":
            break;
        default:
            return gutil.log(gutil.colors.red("Neni nastaveno prostredi!"));
            break;
    }
});
gulp.task('settings-environment-phase3', function () {
    if (!env) {
        return gutil.log(gutil.colors.red("Neni nastaveno prostredi!"));
    }
    return gulp.src(paths.htaccess.src[env]).pipe(rename(paths.htaccess.dest)).pipe(gulp.dest(paths.base));
});

gulp.task('clean-fonts', function () {
    return del(paths.fonts.clean);
});
gulp.task('preprocess-fonts', function () {
    return gulp.src(paths.fonts.src)
        .pipe(gulp.dest(paths.fonts.dest));
});
gulp.task('process-fonts', gulpSequence('clean-fonts', 'preprocess-fonts'));

function processDevImages() {
    return merge(
        // gulp.src(paths.fancybox.images.src)
        //     .pipe(gulp.dest(paths.fancybox.images.dest)),
        // gulp.src(paths.slick.images.src)
        //     .pipe(gulp.dest(paths.slick.images.dest)),
        gulp.src(paths.lightgallery.images.src)
            .pipe(gulp.dest(paths.lightgallery.images.dest)),
        gulp.src(paths.images.src)
            .pipe(gulp.dest(paths.images.dest))
    );
}
function processStageProductionImages() {
    return merge(
        // gulp.src(paths.fancybox.images.src)
        //     .pipe(imagemin())
        //     .pipe(gulp.dest(paths.fancybox.images.dest)),
        // gulp.src(paths.slick.images.src)
        //     .pipe(imagemin())
        //     .pipe(gulp.dest(paths.slick.images.dest)),
        gulp.src(paths.lightgallery.images.src)
            .pipe(imagemin())
            .pipe(gulp.dest(paths.lightgallery.images.dest)),
        gulp.src(paths.images.src)
            .pipe(imagemin())
            .pipe(gulp.dest(paths.images.dest))
    );
}
gulp.task('clean-images', function () {
    return del(paths.images.clean);
});
gulp.task('process-images', function () {
    switch (env) {
        case "dev":
            return processDevImages();
            break;
        case "stage":
            return processStageProductionImages();
            break;
        case "production":
            return processStageProductionImages();
            break;
        default:
            return gutil.log(gutil.colors.red("Neni nastaveno prostredi!"));
            break;
    }
});

function processDevSprites() {
    var spriteData = gulp.src(paths.sprites.src)
        .pipe(spritesmith(spritesmithOptions));
    var imgStream = spriteData.img
        .pipe(gulp.dest(paths.sprites.images.dest));
    var cssStream = spriteData.css
        .pipe(gulp.dest(paths.sprites.styles.dest));
    return merge(imgStream, cssStream);
}
function processStageProductionSprites() {
    var spriteData = gulp.src(paths.sprites.src)
        .pipe(spritesmith(spritesmithOptions));
    var imgStream = spriteData.img
        .pipe(buffer())
        .pipe(imagemin())
        .pipe(gulp.dest(paths.sprites.images.dest));
    var cssStream = spriteData.css
        .pipe(gulp.dest(paths.sprites.styles.dest));
    return merge(imgStream, cssStream);
}
gulp.task('process-sprites', function () {
    switch (env) {
        case "dev":
            return processDevSprites();
            break;
        case "stage":
            return processStageProductionSprites();
            break;
        case "production":
            return processStageProductionSprites();
            break;
        default:
            return gutil.log(gutil.colors.red("Neni nastaveno prostredi!"));
            break;
    }
});

gulp.task('pre-scripts', function (callback) {
    gulpSequence('process-scripts', 'bs-reload', callback);
});
gulp.task('pre-sprites', function (callback) {
    gulpSequence('process-sprites', 'process-sass', callback);
});
gulp.task('pre-images', function (callback) {
    gulpSequence('clean-images', 'process-images', 'bs-reload', callback);
});
gulp.task('change-files', function () {
    if (browserSyncState) {
        browserSync.reload();
    } else {
        return gutil.log(gutil.colors.bgBlack("BrowserSync je vypnut!"));
    }
});

gulp.task('watch', function () {
    gulp.watch(paths.watch.js, ['pre-scripts']).on('change', function () {
        if (browserSyncState) {
            browserSync.notify("<span style='color: #99cc99;'>JS has been compiled!</span>");
        }
    });
    gulp.watch(paths.watch.sass, ['process-sass']).on('change', function () {
        if (browserSyncState) {
            browserSync.notify("<span style='color: #99cc99;'>Sass has been compiled!</span>");
        }
    });
    gulp.watch(paths.watch.sprites, ['pre-sprites']).on('change', function () {
        if (browserSyncState) {
            browserSync.notify("<span style='color: #99cc99;'>Sprites has been processed!</span>");
        }
    });
    gulp.watch(paths.watch.images, ['pre-images']).on('change', function () {
        if (browserSyncState) {
            browserSync.notify("<span style='color: #99cc99;'>Images has been processed!</span>");
        }
    });
    gulp.watch(paths.watch.files, ['change-files']).on('change', function () {
        if (browserSyncState) {
            browserSync.notify("<span style='color: #99cc99;'>Files has been edited!</span>");
        }
    });
});

function processDevSass() {
    var isErr = false;
    return gulp.src(paths.sass.src)
        .pipe(sourcemaps.init())
        .pipe(sass(sassOptions).on('error', function (err) {
            isErr = true;
            gutil.log(gutil.colors.red(err.message));
            browserSync.notify("Error has occurred during compiling of Sass: <span style='color: #f99157;'>" + err.message + "</span>", 10000);
            this.emit('end');
        }))
        // .pipe(autoprefixer(autoprefixerOptions))
        // .pipe(pixrem(pixremOptions))
        // .pipe(postcss([require('postcss-flexibility')]))
        .pipe(sourcemaps.write(paths.base))
        .pipe(gulp.dest(paths.sass.dest).on('end', function () {
            if (isErr === false && browserSyncState === true) {
                browserSync.reload();
            }
        }));
}
function processStageSass() {
    return gulp.src(paths.sass.src)
        .pipe(sourcemaps.init())
        .pipe(sass(sassOptions).on('error', function (err) {
            gutil.log(gutil.colors.red(err.message));
            browserSync.notify("Error has occurred during compiling of Sass: <span style='color: #f99157;'>" + err.message + "</span>", 10000);
            this.emit('end');
        }))
        // .pipe(autoprefixer(autoprefixerOptions))
        // .pipe(pixrem(pixremOptions))
        // .pipe(postcss([require('postcss-flexibility')]))
        .pipe(sourcemaps.write(paths.base))
        .pipe(gulp.dest(paths.sass.dest));
}
function processProductionSass() {
    return gulp.src(paths.sass.src)
        .pipe(sass(sassOptions).on('error', function (err) {
            gutil.log(gutil.colors.red(err.message));
            browserSync.notify("Error has occurred during compiling of Sass: <span style='color: #f99157;'>" + err.message + "</span>", 10000);
            this.emit('end');
        }))
        .pipe(autoprefixer(autoprefixerOptions))
        .pipe(pixrem(pixremOptions))
        .pipe(postcss([require('postcss-flexibility')]))
        .pipe(rename(paths.sass.min))
        .pipe(cssnano(cssnanoOptions))
        .pipe(gulp.dest(paths.sass.dest));
}
gulp.task('process-sass', function () {
    switch (env) {
        case "dev":
            return processDevSass();
            break;
        case "stage":
            return processStageSass();
            break;
        case "production":
            return processProductionSass();
            break;
        default:
            return gutil.log(gutil.colors.red("Neni nastaveno prostredi!"));
            break;
    }
});

gulp.task('preprocess-styles', gulpSequence('clean-preprocess-styles', 'merge-preprocess-styles'));
gulp.task('clean-preprocess-styles', function () {
    return del(paths.cleanPreprocessStyles);
});
gulp.task('merge-preprocess-styles', function () {

});

function processDevScripts() {
    return gulp.src(paths.scripts.src)
        .pipe(sourcemaps.init())
        .pipe(concat(paths.scripts.name))
        .pipe(sourcemaps.write(paths.base))
        .pipe(gulp.dest(paths.scripts.dest));
}
function processStageScripts() {
    return gulp.src(paths.scripts.src)
        .pipe(sourcemaps.init())
        .pipe(concat(paths.scripts.name))
        // .pipe(concat(paths.scripts.min))
        // .pipe(minify({
        //     ext:{
        //         src:'-min.js',
        //         min:'.min.js'
        //     }
        // }))
        .pipe(sourcemaps.write(paths.base))
        .pipe(gulp.dest(paths.scripts.dest));
}
function processProductionScripts() {
    return gulp.src(paths.scripts.src)
        .pipe(concat(paths.scripts.min))
        .pipe(minify({
            ext:{
                src:'-min.js',
                min:'.min.js'
            }
        }))
        //.pipe(rename({suffix: '.min' }))
        .pipe(gulp.dest(paths.scripts.dest));
}
gulp.task('process-scripts', function () {
    switch (env) {
        case "dev":
            return processDevScripts();
            break;
        case "stage":
            return processStageScripts();
            break;
        case "production":
            return processProductionScripts();
            break;
        default:
            return gutil.log(gutil.colors.red("Neni nastaveno prostredi!"));
            break;
    }
});

gulp.task('set-env-dev', function () {
    env = "dev";
    gutil.log(gutil.colors.yellow("Nastavil jsem development prostredi!"));
});
gulp.task('set-env-stage', function () {
    env = "stage";
    gutil.log(gutil.colors.yellow("Nastavil jsem stage prostredi!"));
});
gulp.task('set-env-production', function () {
    env = "production";
    gutil.log(gutil.colors.yellow("Nastavil jsem production prostredi!"));
});
