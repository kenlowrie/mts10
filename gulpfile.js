var gulp = require('gulp'),
	gutil = require('gulp-util'),
	gulpif = require('gulp-if'),
	postcss = require('gulp-postcss'),
	precss = require('precss'),
	debug = require('gulp-debug'),
	cssnano = require('cssnano'),
	uglify = require('gulp-uglify'),
	autoprefixer = require('autoprefixer'),
	cached = require('gulp-cached'),
	include = require('gulp-html-tag-include'),
	minifyHTML = require('gulp-minify-html');

var env,
	srcDir,
	bldRoot,
	htmlSources,
	incSources,
	jsSources,
	cssSources,
	outDir;

env = process.env.NODE_ENV || 'dev';
srcDir = 'src/';
bldRoot = 'build/';
htmlSources = [srcDir + '**/*.html'];
incSources = [srcDir + 'inc/*'];
jsSources = [srcDir + 'js/*.js'];
phpSources = [srcDir + 'php/*', srcDir + 'auth/*.php'];
phpGulpSrc = [srcDir + 'gulpfiles/*.js'];
txtFiles = [srcDir + 'txt/*'];
cssSources = [srcDir + '**/styles.css'];

if (env === 'dev'){
	outDir = bldRoot + 'dev/';
} else {
	outDir = bldRoot + 'rel/';
}

console.log('Building mts10 in ' + env + ' mode to ' + outDir);

gulp.task('cptxt', function(){
   gulp.src(txtFiles)
    .pipe(cached('txtcache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpinc', function(){
   gulp.src(incSources) 
    .pipe(include())
	.on('error', gutil.log)
    .pipe(cached('inccache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpjs', function(){
   gulp.src(jsSources,{base: srcDir}) 
    .pipe(gulpif(env === 'rel', uglify()))
    .pipe(cached('jscache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpphp', function(){
   gulp.src(phpSources) 
    .pipe(cached('phpcache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpgulpphpsrv', function(){
   gulp.src(phpGulpSrc)
   	.pipe(debug())
  	.pipe(gulp.dest(outDir));
});

gulp.task( 'html', ['cpinc'], function() {
	gulp.src(htmlSources)
	    .pipe(include())
		.on('error', gutil.log)
	    .pipe(cached('htmlcache'))
		.pipe(gulpif(env === 'rel', minifyHTML()))
		.pipe(gulp.dest(outDir))
});

var postcssTasks = [precss(),autoprefixer()];
if (env === 'rel'){
	postcssTasks = [precss(),autoprefixer(),cssnano()];
}

gulp.task( 'css', function() {
	gulp.src(cssSources)
		.pipe(postcss(postcssTasks))
		.on('error', gutil.log)
		.pipe(gulp.dest(outDir))
});

gulp.task('watch', function() {
  gulp.watch(srcDir + '**/*.css', ['css']);
  gulp.watch(srcDir + '**/*.html', ['html']);
  gulp.watch(srcDir + 'inc/*', ['html']);
  gulp.watch(srcDir + 'tmpl/*', ['html']);
  gulp.watch(srcDir + 'php/*', ['cpphp']);
  gulp.watch(srcDir + 'js/*.js', ['cpjs']);
  gulp.watch(srcDir + 'txt/*', ['cptxt']);
  gulp.watch(srcDir + 'gulpfiles/*.js', ['cpgulpphpsrv']);
  
});

var buildtasks=['cptxt', 'html', 'css', 'cpphp', 'cpjs', 'cpgulpphpsrv'];

gulp.task('build', buildtasks); 

gulp.task('default', buildtasks.concat(['watch']));
