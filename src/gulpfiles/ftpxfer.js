'use strict';

var gulp = require('gulp');  
var sftp = require( 'gulp-sftp-up4' );
var fs = require('fs-extra');

/** Configuration **/
var host = process.env.FTP_HOST;  
var auth = 'privateKeyCustom';
var authFile = '../../.ftppass';
var localFilesGlob = ['**/*', '!ftpxfer.js', '!phpserver.js'];  

/*function checkForFTPPassOverride(ftpPassFileName) {

	var sourceName = '../../' + ftpPassFilename;
	
	try {
		fs.copySync(sourceName, authFilename)
		console.log("Auth override for " + authFilename + " successful!");
	} catch (err) {
		console.error("Autho override for " + authFilename + " failed.\r\n" + err);
	}
}*/


//var remoteFolder = '/public_html/sd/mts10'
var remoteFolder = '/var/www/vhosts/klowrie.net/httpdocs/sd/mts10'

function deploy(){
    return gulp.src(localFilesGlob, {base: '.', buffer: false })
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
 * Usage: `FTP_USER=someuser FTP_PWD=somepwd gulp ftp-deploy`
 */
gulp.task('ftp-deploy', function() {
    return deploy();
});

/**
 * Watch deploy task.
 * Watches the local copy for changes and copies the new files to the server whenever an update is detected
 *
 * Usage: `FTP_HOST=some.ip.add.r gulp ftp-deploy-watch`
 */
gulp.task('ftp-deploy-watch', function() {

    gulp.watch(localFilesGlob)
    .on('change', function(event) {
      console.log('Changes detected! Uploading file "' + event.path + '", ' + event.type);

      return deploy();
    });
});

gulp.task('default',gulp.series(['ftp-deploy']));
