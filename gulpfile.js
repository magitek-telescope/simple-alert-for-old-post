var gulp = require("gulp");

var sass = require("gulp-sass");
var pleeease = require("gulp-pleeease");
var sourcemaps = require('gulp-sourcemaps');

var rename  = require("gulp-rename");
var uglify  = require("gulp-uglify");
var plumber = require("gulp-plumber");
var browserSync = require("browser-sync");

var dir = {
	"source": "./src",
	"export": "./res"
};

gulp.task("server", function(){
	browserSync({
        proxy: "wp.potato4d.me",
		files: [
			dir.export+"/**.*"
		]
	});
});

gulp.task("js", function (){
	gulp.src(dir.source + "/js/*.js")
		.pipe(plumber())
		.pipe(gulp.dest(dir.export))
		.pipe(uglify())
		.pipe(rename({
			extname: '.min.js'
		}))
		.pipe(gulp.dest(dir.export))
})

gulp.task("sass", function(){
	gulp.src(dir.source + "/scss/*.scss")
		.pipe(plumber())
		.pipe(sass({outputStyle: 'expanded'}))
		.pipe(gulp.dest(dir.export+"/css"))
		.pipe(sourcemaps.init())
		.pipe(pleeease({
			fallbacks: {
				autoprefixer: ['last 2 versions']
			},
			minifier: true
		}))
		.pipe(sourcemaps.write())
		.pipe(rename({
			extname: '.min.css'
		}))
		.pipe(gulp.dest(dir.export+"/css"));
});

gulp.task("build", ["js", "sass"]);

gulp.task("default", ["server", "js", "sass"], function (){
	gulp.watch([dir.source+"/js/*.js"]    , ["js"]);
	gulp.watch([dir.source+"/scss/*.scss"], ["sass"]);
});
