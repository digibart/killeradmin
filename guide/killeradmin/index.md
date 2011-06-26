Killer Admin
========

Overview
--------

Killer Admin is a Kohana module, meant for rapid development of a admin-area for end-users. The module provides a user-manager and a framework for easy creating administration pages.

Features
-------

* Validating the objects
* Sorting, filtering, adding, editing, and deleting objects
* Specify which user-role can list, create, edit or delete objects
* Minimal (aka no) adjustments to existing models
* Depends only on official modules

Quick start
------------

* Enable the required modules listed below.
* Set up the database: `modules/auth/mysql.sql` or `modules/auth/postgresql.sql`
* Copy `modules/killeradmin/config/admin.sample.php` to you `application/config/admin.php`
* Go to [/admin/setup](http://localhost/admin/setup) to create a user.

Going too fast? There is also a [tutorial](tutorial)

Requirements
------------

Killer Admin requires the following Kohana modules:

* [Auth](http://github.com/kohana/auth)
* [Cache](http://github.com/kohana/cache)
* [Database](http://github.com/kohana/database)
* [Orm](http://github.com/kohana/orm)
* [Pagination](http://github.com/kohana/pagination)
