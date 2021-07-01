# mts10

### Movie Tracking System

Welcome to the Movie Tracking System project. This document should help you get the project up and running on your own server. If you have questions, or would like to provide feedback and/or to report a bug, feel free to contact the author, Ken Lowrie, at [www.kenlowrie.com](https://www.kenlowrie.com/).

### Attributions

#### Installing this app to your server

This is a [Gulp](http://gulpjs.com/) project, so you'll need [Node.js](https://nodejs.org/en/) installed on your build machine in order to put the distribution together. Follow the link above to learn about Gulp and how to set it up on your system, just make sure to install and configure Node.js first.

Once you've installed Node.js, simply checkout the source tree from Github to a local directory on your system, and issue: "npm install" to automatically pull down the various Gulp modules you need to build a distribution.

Then, run "gulp" to build a development version, or "NODE_ENV=rel gulp" to build a release version (the only difference is that your CSS and JS will be minified in the release version.

You will need to modify two files before building the code (or at least before uploading to your server. The files are:

	sql.logininfo.php
	users.logininfo.php
 
These files contain the database name and login information, as well as the default set of users for your movie tracker. I will provide additional information on setting these up before I wrap up this project, but for now, I will let you try to figure it out on your own...

Running Gulp will create a "Build/dev" or "Build/rel" depending on how the NODE_ENV variable is set. Go into the corresponding directory, and then transfer all the files up to your server, maintaining the directory structure, and you'll be all set.

That's it! If you run into any problems, feel free to contact me for assistance.

#### Why Movie Tracking System?

Because I was bored, and got tired of tracking movies and games by hand...

This code was originally written before CSS was a thing. Over the years, I've been removing more and more of the styling from the code and into the stylesheets, and at this point, it's getting pretty close to being all cleaned up.

#### Getting Started with the App

I don't think this app requires much documentation. But again, I will write up some additional basic information after I finish fixing a few more bugs and testing it out on different devices and browsers.

#### Summary

This concludes the documentation on the Movie Tracking System app.
