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
		projectDir + '/js/vendor/thumbnail-slider.js',
		projectDir + '/js/vendor/jquery.flexslider.js',
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
		projectDir + '/js/vendor/thumbnail-slider.js',
		projectDir + '/js/vendor/jquery.flexslider.js',
		projectDir + '/bower-components/angular/angular.js',
		projectDir + '/bower-components/angular-cookies/angular-cookies.js',
		projectDir + '/bower-components/bootstrap/dist/js/bootstrap.js',
		projectDir + '/bower-components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js',
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

gulp.task('watch', gulp.parallel('sass', 'js-libs'), function (sass) {
	gulp.watch(projectDir + '/sass/**/*.sass', gulp.parallel('sass'));
	gulp.watch(projectDir + '/js/*.js', browserSync.reload);
});

gulp.task('imagemin', function () {
	return gulp.src(projectDir + '/img/**/*')
		.pipe(cache(imagemin({
			interlaced: true,
			progressive: true,
			svgoPlugins: gulp.parallel({removeViewBox: false}),
			use: gulp.parallel(pngquant())
		})))
		.pipe(gulp.dest(projectDistImageDir));
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


gulp.task('simple-move-css', function () {
	return gulp.src([
		projectDir + '/css/slider.min.css',
	])
		.pipe(gulp.dest(projectDistDir + '/css'));
});





// const sass_1 = gulp.task('sass_1');
// exports.sync = gulp.series(sass_1);
gulp.task('build', gulp.series('sass', 'js-libs', 'client-js', 'simple-move-fonts', 'bower-components-css', 'simple-move-css'), function () {
});
