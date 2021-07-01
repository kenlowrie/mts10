var gulp = require('gulp'),
	browserSync = require('browser-sync'),
	php = require('gulp-connect-php'),
	fs = require('fs-extra');

// allow overriding the login info creds via files in root folder...
function checkForAuthOverride(authFilename) {

	var sourceName = '../../' + authFilename;
	
	try {
		fs.copySync(sourceName, authFilename)
		console.log("Auth override for " + authFilename + " successful!");
	} catch (err) {
		console.error("Autho override for " + authFilename + " failed.\r\n" + err);
	}
}

gulp.task('override-auth', function(done){

	checkForAuthOverride('users.logininfo.php');
	checkForAuthOverride('sql.logininfo.php');
	done();
});

gulp.task('php', gulp.series(['override-auth'], function(done) {
    php.server({}, function () {
        browserSync({ proxy: '127.0.0.1:8000'});
    });
    
    gulp.watch(['*','css/*', 'js/*','images/*']).on('change', function () {
        browserSync.reload();
    });
}));

gulp.task('default', gulp.series(['php']));
