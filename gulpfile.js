let gulp           = require('gulp'),
		browserSync    = require('browser-sync'),
		sass           = require('gulp-sass'),
		concat         = require('gulp-concat'),
		uglify         = require('gulp-uglify'),
		cleanCSS       = require('gulp-clean-css'),
		rename         = require('gulp-rename'),
		del            = require('del'),
		imagemin       = require('gulp-imagemin'),
		pngquant       = require('imagemin-pngquant'),
		cache          = require('gulp-cache'),
		autoprefixer   = require('gulp-autoprefixer'),
		bourbon        = require('node-bourbon'),
		runSequence    = require('run-sequence');

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
	return gulp.src([
			projectDir + '/js/vendor/jquery-3.3.1.min.js',
			projectDir + '/js/vendor/owl.carousel.min.js',
			projectDir + '/js/vendor/ninja-slider.js',
			projectDir + '/js/vendor/thumbnail-slider.js'
		])
		.pipe(concat('libs.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(projectDistDir + '/js'));
});

gulp.task('client-js', function () {
    return gulp.src([
        projectDir + '/js/client/common.js',
        // projectDir + '/js/client/app.js',
        // projectDir + '/js/client/service/current-user-service.js',
        // projectDir + '/js/client/service/stake-service.js',
        // projectDir + '/js/client/app-controller.js',
        // projectDir + '/js/client/bonus-controller.js',
        // projectDir + '/js/client/main-controller.js',
        // projectDir + '/js/client/security-controller.js',
        // projectDir + '/js/client/login-controller.js',
        // projectDir + '/js/client/registration-controller.js',
        // projectDir + '/js/client/service/update-service.js',
        // projectDir + '/js/client/recommend-auctions-controller.js',
        // projectDir + '/js/client/my-auctions-controller.js',
        // projectDir + '/js/client/auction-detail-controller.js',
        // projectDir + '/js/client/directive/notify-directive.js',
        // projectDir + '/js/client/directive/file-upload-cancel-directive.js',
        // projectDir + '/js/client/directive/show-cut-image-directive.js',
        // projectDir + '/js/client/autostake-controller.js',
        // projectDir + '/js/client/filter/order-object-by-filter.js',
        // projectDir + '/js/client/forgot-password-controller.js',
        //projectDir + '/js/client/service/web-socket-service.js'
    ])
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

gulp.task('build', ['removedist', 'imagemin', 'sass', 'js-libs', 'client-js', 'simple-move-fonts'], function () {
});

gulp.task('removedist', function () { return del.sync(projectDistDir); });

gulp.task('simple-move-fonts', function () {
    return gulp.src([
        projectDir + '/fonts/**/*'
    ])
        .pipe(gulp.dest(projectDistDir + '/fonts'));
});