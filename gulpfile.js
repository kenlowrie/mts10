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
   return gulp.src(txtFiles)
    .pipe(cached('txtcache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpinc', function(){
   return gulp.src(incSources,{base: srcDir}) 
    .pipe(include())
	.on('error', gutil.log)
    .pipe(cached('inccache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpjs', function(){
   return gulp.src(jsSources,{base: srcDir}) 
    .pipe(gulpif(env === 'rel', uglify()))
    .pipe(cached('jscache'))
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpphp', function(){
   return gulp.src(phpSources) 
    .pipe(cached('phpcache'))
	.pipe(debug())
  	.pipe(gulp.dest(outDir));
});

gulp.task('cpgulpphpsrv', function(){
   return gulp.src(phpGulpSrc)
   	.pipe(debug())
  	.pipe(gulp.dest(outDir));
});

gulp.task( 'html', gulp.series('cpinc', function() {
	return gulp.src(htmlSources)
	    .pipe(include())
		.on('error', gutil.log)
	    .pipe(cached('htmlcache'))
		.pipe(gulpif(env === 'rel', minifyHTML()))
		.pipe(gulp.dest(outDir))
}));

var postcssTasks = [precss(),autoprefixer()];
if (env === 'rel'){
	postcssTasks = [precss(),autoprefixer(),cssnano()];
}

gulp.task( 'css', function() {
	return gulp.src(cssSources)
		.pipe(postcss(postcssTasks))
		.on('error', gutil.log)
		.pipe(gulp.dest(outDir))
});

gulp.task('watch', function(done) {
  gulp.watch(srcDir + '**/*.css', gulp.series(['css']));
  gulp.watch(srcDir + '**/*.html', gulp.series(['html']));
  gulp.watch(srcDir + 'inc/*', gulp.series(['html']));
  gulp.watch(srcDir + 'tmpl/*', gulp.series(['html']));
  gulp.watch(srcDir + 'php/*', gulp.series(['cpphp']));
  gulp.watch(srcDir + 'js/*.js', gulp.series(['cpjs']));
  gulp.watch(srcDir + 'txt/*', gulp.series(['cptxt']));
  gulp.watch(srcDir + 'gulpfiles/*.js', gulp.series(['cpgulpphpsrv']));
  done();
});

var buildtasks=['cptxt', 'html', 'css', 'cpphp', 'cpjs', 'cpgulpphpsrv'];

gulp.task('build', gulp.series(buildtasks)); 

gulp.task('default', gulp.parallel(buildtasks.concat(['watch'])));
