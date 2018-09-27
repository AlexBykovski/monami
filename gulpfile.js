let gulp           = require('gulp'),
		browserSync    = require('browser-sync'),
		sass           = require('gulp-sass'),
		concat         = require('gulp-concat'),
		uglify         = require('gulp-uglify'),
		cleanCSS       = require('gulp-clean-css'),
		rename         = require('gulp-rename'),
		del            = require('del'),
		imagemin       = require('gulp-imagemin'),
//		pngquant       = require('imagemin-pngquant'),
		cache          = require('gulp-cache'),
		autoprefixer   = require('gulp-autoprefixer'),
		bourbon        = require('node-bourbon'),
		runSequence    = require('run-sequence');
let babel = require('gulp-babel');

let projectDir = 'assets';
let projectDistDir = 'public/build';
let projectDistImageDir = 'public/images/default';

gulp.task('sass', function () {
	return gulp.src(projectDir + '/sass/*.sass')
		.pipe(sass({
			includePaths: bourbon.includePaths
		}).on('error', sass.logError))
		.pipe(rename({suffix: '.min', prefix : ''}))
		.pipe(autoprefixer(['last 15 versions']))
		.pipe(cleanCSS())
		.pipe(gulp.dest(projectDistDir + '/css'))
		.pipe(browserSync.reload({stream: true}))
});

gulp.task('js-libs', function () {
    gulp.src([
        projectDir + '/bower-components/jquery/dist/jquery.js',
        projectDir + '/js/vendor/owl.carousel.min.js',
        projectDir + '/js/vendor/ninja-slider.js',
        projectDir + '/js/vendor/thumbnail-slider.js',
        projectDir + '/bower-components/angular/angular.js',
        projectDir + '/bower-components/angular-cookies/angular-cookies.min.js',
        projectDir + '/bower-components/bootstrap/dist/js/bootstrap.js',
        projectDir + '/bower-components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js',
        projectDir + '/bower-components/flexslider/jquery.flexslider-min.js'
    ])
        .pipe(gulp.dest(projectDistDir + '/js/libs'));

	return gulp.src([
        	projectDir + '/bower-components/jquery/dist/jquery.js',
        	projectDir + '/js/vendor/owl.carousel.min.js',
        	projectDir + '/js/vendor/ninja-slider.js',
			projectDir + '/js/vendor/thumbnail-slider.js',
			projectDir + '/bower-components/angular/angular.js',
			projectDir + '/bower-components/angular-cookies/angular-cookies.js',
			projectDir + '/bower-components/bootstrap/dist/js/bootstrap.js',
			projectDir + '/bower-components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js',
        	projectDir + '/bower-components/flexslider/jquery.flexslider-min.js'
		])
		.pipe(concat('libs.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('client-js', function () {
    return gulp.src([
        projectDir + '/js/client/common.js',
		projectDir + '/js/client/app.js',
		projectDir + '/js/client/controller/app-controller.js',
		projectDir + '/js/client/controller/filter-products-controller.js',
		projectDir + '/js/client/controller/cart-controller.js',
		projectDir + '/js/client/controller/search-controller.js',
		projectDir + '/js/client/service/cart-service.js',
        projectDir + '/js/client/directive/notify-directive.js',
    ])
        .pipe(babel({
            presets: ['babel-preset-es2015']
        }))
        .pipe(concat('client.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('watch', ['sass', 'js-libs'], function () {
	gulp.watch(projectDir + '/sass/**/*.sass', ['sass']);
	gulp.watch(projectDir + '/js/*.js', browserSync.reload);
});

gulp.task('imagemin', function () {
	return gulp.src(projectDir + '/img/**/*')
		.pipe(cache(imagemin({
			interlaced: true,
			progressive: true,
			svgoPlugins: [{removeViewBox: false}],
			use: [pngquant()]
		})))
		.pipe(gulp.dest(projectDistImageDir));
});

gulp.task('build', ['sass', 'js-libs', 'client-js', 'simple-move-fonts', 'bower-components-css'], function () {
});

gulp.task('removedist', function () { return del.sync(projectDistDir); });

gulp.task('simple-move-fonts', function () {
    return gulp.src([
        projectDir + '/fonts/**/*'
    ])
        .pipe(gulp.dest(projectDistDir + '/fonts'));
});

gulp.task('bower-components-css', function () {
    return gulp.src([
        projectDir + '/bower-components/bootstrap/dist/css/bootstrap.css',
    ])
        .pipe(concat('bower_components.css'))
        .pipe(gulp.dest(projectDistDir + '/css'));
});