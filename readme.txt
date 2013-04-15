=== IMDb Markup Syntax ===
Contributors: HenrikRoos
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YRT2ALPQH42N4
Tags: IMDb, IMBb API, Movie, Filter
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.0
License: GPL-3.0
License URI: http://opensource.org/licenses/gpl-3.0.html

Add IMDb syntax functionallity in your post. Enter simple tags and this plugin
replace with IMBb data direct from IMBb Database.


== Description ==
This plugin extends your writing with a markup syntax for display movie data in your
Post content and title. Datasource is native IMDb Web Service same datasource IMDb:s
Mobile apps using [IMDb Mobile Applications](http://app.imdb.com).

### Example
In post *edit* mode you write:

```
[imdb:id(tt0110912)]
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros
non dui porta tincidunt. Nulla ut mi eget justo ultrices auctor sed in lacus.

Title: [imdb:title]
Release Date: [imdb:date]

Vivamus id sem felis. Donec consequat urna et sapien gravida bibendum sed ut orci.
Donec eu nibh leo. Etiam hendrerit justo eget est vehicula eu ornare dolor vulputate. 
```
**After** you save it is transform to:

```
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros
non dui porta tincidunt. Nulla ut mi eget justo ultrices auctor sed in lacus.

Title: Pulp Fiction
Release Date: 1994-10-14

Vivamus id sem felis. Donec consequat urna et sapien gravida bibendum sed ut orci.
Donec eu nibh leo. Etiam hendrerit justo eget est vehicula eu ornare dolor vulputate. 
```


== IMDb data copyright ===
Limited non-commercial use of IMDb data is allowed, provided the following conditions
are met:

1. You agree to all the terms of our
[copyright/conditions](http://www.imdb.com/help/show_article?conditions) of use
statement. Please also note that IMDb reserves the right to withdraw permission to 
use the data at any time at our discretion.
2. The data must be taken only direct from IMDb. You may not use data mining, robots,
screen scraping, or similar online data gathering and extraction tools on our
website.
3. The data can only be used for **personal and non-commercial** use and must not be
altered/republished/resold/repurposed to create any kind of online/offline database
of movie information (except for **individual personal** use). Please refer to the
copyright/license information enclosed in each file for further instructions and
limitations on allowed usage.
4. You must acknowledge the source of the data by including the following statement:
>Information courtesy of [The Internet Movie Database](http://www.imdb.com). Used
>with permission.

== Installation ==

### Install
1. Press ** Install Now** 
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ready to use.  

### Uninstall
1. Deactivate the plugin through the 'Plugins' menu in WordPress
2. Press **Delete**
3. And conform that you want to delete this plugin. No cleanup is necessary, this plugin right no files or data in your database. 

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.
