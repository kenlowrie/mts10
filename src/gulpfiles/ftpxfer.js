'use strict';

var gulp = require('gulp');  
var sftp = require( 'gulp-sftp-up4' );
var fs = require('fs-extra');

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

function handleOverrides(){
	checkForAuthOverride('users.logininfo.php');
	checkForAuthOverride('sql.logininfo.php');
}

/** Configuration **/
var host = process.env.FTP_HOST;  
var auth = 'privateKeyCustom';
var authFile = '../../.ftppass';
var localFilesGlob = ['**/*', '!ftpxfer.js', '!phpserver.js'];  

var remoteFolder = '/var/www/vhosts/klowrie.net/httpdocs/sd/mts10'

function deploy(files){
    return gulp.src(files, {base: '.', buffer: false })
        .pipe( sftp({
            host: host,
            auth: auth,
            authFile: authFile,
            remotePath: remoteFolder
        }));
}
/**
 * Deploy task.
 * Copies the new files to the server
 *
 * Usage: `FTP_HOST=some.ip.add.r gulp -f ftpxfer ftp-deploy`
 */
gulp.task('ftp-deploy', function() {
    handleOverrides();
    return deploy(localFilesGlob);
});

/**
 * Watch deploy task.
 * Watches the local copy for changes and copies the new files to the server whenever an update is detected
 *
 * Usage: `FTP_HOST=some.ip.add.r gulp -f ftpxfer ftp-deploy-watch`
 */
gulp.task('ftp-deploy-watch', function() {

    handleOverrides();
    gulp.watch(localFilesGlob)
    .on('change', function(path, stats) {
      console.log('Changes detected! Uploading file "' + path);

      return deploy(path);
    });
});

gulp.task('default',gulp.series(['ftp-deploy']));
