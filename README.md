# file-server
Simply a server to manage files. Files are referenced by a uuid v4.  
This project manages your uploaded files, so file upload isn't everywhere inside your project code.
An uuid v4 is the only needed reference to use the file.

## Goals
The goal is to make a server which allows file upload and returns a uuid v4 as a reference to that file.  
Access to files is done through a route, so the server can keep track of the latest access for each file.  
The server should be able to automatically clean up files which haven't been accessed in a long time (configurable), or disable this option to retain files forever.
It should be possible to overwrite existing files given an uuid to match, so it is possible to update an existing file with a newer version.
Don't keep track of newer versus older versions (a new version will just retain the new version and no longer the old version)

## Roadmap
* [x] File upload
* [x] Referencing each upload with a uuid v4
* [ ] Track file access
* [ ] Auto clean files based on file access
* [ ] Make auto cleaning configurable on a deployment basis
* [ ] Make auto cleaning configurable on a file basis
