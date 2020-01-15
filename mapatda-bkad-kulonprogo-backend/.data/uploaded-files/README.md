### Directory for uploaded files

This directory is soft-linked with img/uploaded-files in application's FE directory.

Create a soft-link:
```
$ sudo ln -s [.../.data/uploaded-files] [.../img/uploaded-files]
$ sudo chmod 777 [.../img/uploaded-files]
```