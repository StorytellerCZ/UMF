UMF
===

Users - Messages - Forums -- This is an advance package of A3M that adds to its basis functionality private messages and simple forums to kickstart the development of web apps.

### Author

**AdwinTrave** [@Storyteller_cz](https://twitter.com/Storyteller_cz)

### Dependencies
* PHP 5.3
* CURL
* DOM or domxml
* GMP or Bcmatch

### UMF came from merging of
* [A3M](https://github.com/donjakobo/A3M/)
* [Mahana Messaging Library](https://github.com/jrmadsen67/Mahana-Messaging-library-for-CodeIgniter)
* [CIBB](http://superdit.com/2012/08/15/cibb-an-experimental-basic-forum-built-with-codeigniter-and-twitter-bootstrap/)

### Installation
1) Upload all the files to your server

2) Setup database and import into it the content of `database.sql`

3) Go through `application/config/config.php` and `application/config/database.php` and make sure that all the values are set correctly.

4) Adjust the settings in `application/config/umf/` to your liking.

5) Go to `application/language/english/general_lang.php` and change the default page name there.

6) You are all set. First user to register on the webpage is going to become the admin.
