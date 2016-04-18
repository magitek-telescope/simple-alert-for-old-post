var gulp = require("gulp");

var sass = require("gulp-sass");
var bulkSass = require("gulp-sass-bulk-import");
var pleeease = require("gulp-pleeease");
var sourcemaps = require('gulp-sourcemaps');

var rename  = require("gulp-rename");
var uglify  = require("gulp-uglify");
var plumber = require("gulp-plumber");
var browserSync = require("browser-sync");

var dir = {
	"source": "./src",
	"dest": "./res"
};

gulp.task("server", function(){
	browserSync({
        proxy: "wp.potato4d.me",
		files: [
			dir.dest+"/**.*",
			"**.php",
			"**/**.php"
		]
	});
});

gulp.task("js", function (){
	gulp.src(dir.source + "/js/*.js")
		.pipe(plumber())
		.pipe(gulp.dest(dir.dest+"/js"))
		.pipe(uglify())
		.pipe(rename({
			extname: '.min.js'
		}))
		.pipe(gulp.dest(dir.dest+"/js"))
})

gulp.task("sass", function(){
	gulp.src(dir.source + "/scss/*.scss")
		.pipe(plumber())
		.pipe(bulkSass())
		.pipe(sass({outputStyle: 'expanded'}))
		.pipe(sourcemaps.init())
		.pipe(pleeease({
			fallbacks: {
				autoprefixer: ['last 2 versions']
			},
			minifier: false
		}))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(dir.dest+"/css"))
		.pipe(pleeease({
			minifier: true
		}))
		.pipe(rename({
			extname: '.min.css'
		}))
		.pipe(gulp.dest(dir.dest+"/css"));
});

gulp.task("build", ["js", "sass"]);

gulp.task("default", ["server", "js", "sass"], function (){
	gulp.watch([dir.source+"/js/*.js"]    , ["js"]);
	gulp.watch([dir.source+"/**/*.scss"], ["sass"]);
});
