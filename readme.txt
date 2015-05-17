=== IMDb Markup Syntax ===
Contributors: HenrikRoos
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YRT2ALPQH42N4
Tags: IMDb, Movie, Review, API, Markup, Syntax, Clean
Requires at least: 3.3
Tested up to: 4.2.2
Stable tag: 2.5
License: GPL-3.0
License URI: http://opensource.org/licenses/gpl-3.0.html

Add IMDb syntax functionallity in your post. Enter simple tags and this plugin
replace with IMBb data direct from IMDb Mobile Applications.

== Description ==
This plugin makes it possible to insert movie data in your text from the IMDb Web Service which is the same datasource that IMDb:s Mobile apps is using [IMDb Mobile Applications](http://app.imdb.com). The plugin is

 * **Stable:** over 120 unit test.
 * **Clean:** no configuration, well integrated to WordPress API, no checkstyle errors.
 * **Fast:** No extra database writes, using only filter hooks (no actions hooks). IMDb DataSource is an RESTful interface.
 * **Internationalizing:** Support for locale from IMBb datasource, date format and number format.
 * **Error handling:** Well design and well tested error handling.

= Simple example =
In post *edit* mode you write:

	[imdb:id(tt0110912)]
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros non dui porta tincidunt.
	Nulla ut mi eget justo ultrices auctor sed in lacus.

	Title: [imdb:title]
	Release Date: [imdb:date]

	Vivamus id sem felis. Donec consequat urna et sapien gravida bibendum sed ut orci. Donec eu nibh leo.
	Etiam hendrerit justo eget est vehicula eu ornare dolor vulputate. 

**After** you save it is transform to:

	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros non dui porta tincidunt.
	Nulla ut mi eget justo ultrices auctor sed in lacus.

	Title: Pulp Fiction
	Release Date: 1994-10-14

	Vivamus id sem felis. Donec consequat urna et sapien gravida bibendum sed ut orci. Donec eu nibh leo.
	Etiam hendrerit justo eget est vehicula eu ornare dolor vulputate.

= Set language for a set av tags =
In post *edit* mode you write:
	[imdb:id(tt0110912)]
	[imdb:locale(de_DE)]
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros non dui porta tincidunt.
	Nulla ut mi eget justo ultrices auctor sed in lacus.

	Title: [imdb:title]
	Release Date: [imdb:date]

	[imdb:id(tt0110912)]
    [imdb:locale(es)]
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros non dui porta tincidunt.
    Nulla ut mi eget justo ultrices auctor sed in lacus.

    Title: [imdb:title]
    Release Date: [imdb:date]


= List of movies =
In post *edit* mode you write:

    [imdb-WhatIf:id(tt1486834)]
    [imdblive-WhatIf:id(tt1486834)]
    <h1>[imdb-WhatIf:title_nolink]</h1>
    Ratings: [imdblive-WhatIf:rating]/10 from [imdblive-WhatIf:votes] users
    <div>[imdb-WhatIf:poster]</div>

    [imdb-AboutAlex:id(tt2667918)]
    [imdblive-AboutAlex:id(tt2667918)]
    <h1>[imdb-AboutAlex:title_nolink]</h1>
    Ratings: [imdblive-AboutAlex:rating]/10 from [imdblive-AboutAlex:votes] users
    <div>[imdb-AboutAlex:poster]</div>

= All tags example =
This example display all implements tags in one post. For you own test: cut and paste this example in a new post and save it.

	[imdb:id(tt1951264)]
	[imdblive:id(tt1951264)]
	<table>
		<tr>
			<th>Tag description</th>
			<th>imdb tag (static)</th>
			<th>imdblive tag (dynamic)</th>
		</tr>
		<tr>
			<td>Cast (A list of main actors)</td>
			<td>[imdb:cast]</td>
			<td>[imdblive:cast]</td>
		</tr>
		<tr>
			<td>Cast (A list of main actors) no link</td>
			<td>[imdb:cast_nolink]</td>
			<td>[imdblive:cast_nolink]</td>
		</tr>
		<tr>
			<td>Certificate (Recommended age in your country)</td>
			<td>[imdb:certificate]</td>
			<td>[imdblive:certificate]</td>
		</tr>
		<tr>
			<td>Date (The day when a movie is shipped to exhibitors in your country)</td>
			<td>[imdb:date]</td>
			<td>[imdblive:date]</td>
		</tr>
		<tr>
			<td>Directors (The principal creative artist on a movie set)</td>
			<td>[imdb:directors]</td>
			<td>[imdblive:directors]</td>
		</tr>
		<tr>
			<td>Directors (The principal creative artist on a movie set) no link</td>
			<td>[imdb:directors_nolink]</td>
			<td>[imdblive:directors_nolink]</td>
		</tr>
		<tr>
			<td>Genres (One or more genres for current movie)</td>
			<td>[imdb:genres]</td>
			<td>[imdblive:genres]</td>
		</tr>
		<tr>
			<td>Plot (Description)</td>
			<td>[imdb:plot]</td>
			<td>[imdblive:plot]</td>
		</tr>
		<tr>
			<td>Poster (Current movie poster image from your lib)</td>
			<td><div>[imdb:poster]</div></td>
			<td>N/A</td>
		</tr>
		<tr>
			<td>Poster (Current movie poster image from your lib) no link</td>
			<td><div>[imdb:poster_nolink]</div></td>
			<td>N/A</td>
		</tr>
		<tr>
			<td>Poster Remote (Current movie poster image direct linked from imdb server) no link</td>
			<td>[imdb:posterRemote_nolink]</td>
			<td><div>[imdblive:posterRemote_nolink]</div></td>
		</tr>
		<tr>
			<td>Poster Remote (Current movie poster image direct linked from imdb server)</td>
			<td>[imdb:posterRemote]</td>
			<td><div>[imdblive:posterRemote]</div></td>
		</tr>
		<tr>
			<td>Rating (Rating scale from 1 to 10 with one decimal)</td>
			<td>[imdb:rating]</td>
			<td>[imdblive:rating]</td>
		</tr>
		<tr>
			<td>Runtime (Runtime in minutes for current movie)</td>
			<td>[imdb:runtime]</td>
			<td>[imdblive:runtime]</td>
		</tr>
		<tr>
			<td>Tagline (Branding slogan)</td>
			<td>[imdb:tagline]</td>
			<td>[imdblive:tagline]</td>
		</tr>
		<tr>
			<td>Tconst (IMDb ID)</td>
			<td>[imdb:tconst]</td>
			<td>[imdblive:tconst]</td>
		</tr>
		<tr>
			<td>Title (Prefered in your language)</td>
			<td>[imdb:title]</td>
			<td>[imdblive:title]</td>
		</tr>
		<tr>
			<td>Title (Prefered in your language) no link</td>
			<td>[imdb:title_nolink]</td>
			<td>[imdblive:title_nolink]</td>
		</tr>
		<tr>
			<td>Type (IMDb classifies)</td>
			<td>[imdb:type]</td>
			<td>[imdblive:type]</td>
		</tr>
		<tr>
			<td>Votes (Number of votes from imdb members)</td>
			<td>[imdb:votes]</td>
			<td>[imdblive:votes]</td>
		</tr>
		<tr>
			<td>Writers (Someone who creates a written work)</td>
			<td>[imdb:writers]</td>
			<td>[imdblive:writers]</td>
		</tr>
		<tr>
			<td>Writers (Someone who creates a written work) no link</td>
			<td>[imdb:writers_nolink]</td>
			<td>[imdblive:writers_nolink]</td>
		</tr>
		<tr>
			<td>Year (Publication year)</td>
			<td>[imdb:year]</td>
			<td>[imdblive:year]</td>
		</tr>
	</table>

== Installation ==
1. Upload 'imdb-markup-syntax' to the '/wp-content/plugins/' directory,
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. PHP 5.3+ with PECL json 1.2+ and cURL is required.

= How to use the plugin on your blog =
You have watched a movie and want to post a comment on it on your blog. With a few simple steps you can use this plugin to extract movie facts and display it on your blog which will complement your writing with professional and exact facts about the movie. 

1. Go to [IMDb](http://imdb.com/) and search the movie you want to write about.
2. Copy the movie id tag shown in the url, for example: *http://www.imdb.com/title/**tt0081505*** i.e. **tt0081505** This tag will help you to insert facts from the movie that will be displayed and highlighted when you are writing the movie title, name of the actors, the directors and so on. 
3. For example: *[imdb:id(tt0081505)] Yesterday I saw an old movie, [imdb:title]. I really liked both the [imdb:directors] and the movie*
4. Your text will be displayed as: *Yesterday I saw an old movie, The Shining. I really liked both the [Stanley Kubrick](http://www.imdb.com/name/nm0000040) and the movie*

You can also insert statistics such as the number of people who has comment on the movie at IMDb´s website and this will be automatically updated whenever your own blog page is refreshed.

== Upgrade Notice ==
Current version is backward compatibility.

== Other Notes ==
= Markup Syntax =

    [imdb:id(tt0000000)]

Set the current movie. All tags starting with `[imdb` use this id. This ID disappearance when you **save** the post into your database.

    [imdblive:id(tt0000000)]

Set the current movie. All tags starting with `[imdblive` use this id. This ID disappearance when you **read** the post from your database.

    [imdb:locale(en)] or [imdblive:locale(sv_se)]

Set the current language in standard RFC 4646. All tags starting with `[imdb [imdblive` use this language (if imdb.com support that).

    [imdb:cast] or [imdblive:cast]

A list of main actors (Updated 2.5). *<br />Example:<br />[Elijah Wood](http://www.imdb.com/name/nm0000704) Frodo Baggins<br />[Ian McKellen](http://www.imdb.com/name/nm0005212) Gandalf the Grey<br />[Orlando Bloom](http://www.imdb.com/name/nm0089217) Legolas Greenleaf<br />[Sean Bean](http://www.imdb.com/name/nm0000293) Boromir*

    [imdb:cast_nolink] or [imdblive:cast_nolink]

A list of main actors with no links (Required 2.0, Updated 2.5). *<br />Example:<br />Elijah Wood, Ian McKellen, Orlando Bloom, Sean Bean*

    [imdb:certificate] or [imdblive:certificate]

Various countries or regions have film classification boards for reviewing movies and rating their content in terms of its suitability for particular audiences. For many countries, movies are required to be advertised as having a particular "certificate" or "rating", forewarning audiences of possible "objectionable content". The nature of this "objectionable content" is determined mainly by contemporary national, social, religious, and political standards. The usual criteria which determine a film's certificate are violence and sexuality, with "mature" (adult) situations and especially blasphemy and political issues often being considered more important outside the Western world. This is by no means a hard and fast rule; see the Hays Production Code for an example. In some cases, a film classification board exhibits censorship by demanding changes be made to a movie in order to receive a certain rating. As many movies are targeted at a particular age group, studios must balance the content of their films against the demands of the classification board. Negotiations are common; studios agree to make certain changes to films in order to receive the required rating. The IMDb uses the term "Certificate" as opposed to "Rating" to avoid confusion with "ratings" meaning the opinions of critics. <http://www.filmratings.com> Classification and Rating Administration (CARA)<br /><br />*NOTE: This tag has language dependency, different WordPress language different output.<br /><br />Example: Fight Club has certificate **PG** on english WordPress and **15** on swedish WordPress*

    [imdb:date] or [imdblive:date]

The day when a movie is shipped to exhibitors by the distributor, it is deemed to have been released for public viewing - there are no longer any studio restrictions on who can see the movie. If no release date is given as used publication year.<br /><br />*NOTE: This tag has language dependency, different WordPress language different output.<br /><br />Example: Pulp Fiction has release date **Fri Oct 14 1994** on english WordPress and **Fre 25 Nov 1994** on swedish WordPress*

    [imdb:directors] or [imdblive:directors]

The principal creative artist on a movie set. A director is usually (but not always) the driving artistic source behind the filming process, and communicates to actors the way that he/she would like a particular scene played. A director's duties might also include casting, script editing, shot selection, shot composition, and editing. Typically, a director has complete artistic control over all aspects of the movie, but it is not uncommon for the director to be bound by agreements with either a producer or a studio. In some large productions, a director will delegate less important scenes to a second unit.*<br /><br />Example: Director of Pan's Labyrinth is **[Guillermo del Toro](http://www.imdb.com/name/nm0868219)***

    [imdb:directors_nolink] or [imdblive:directors_nolink]

The principal creative artist on a movie set. A director is usually (but not always) the driving artistic source behind the filming process, and communicates to actors the way that he/she would like a particular scene played. A director's duties might also include casting, script editing, shot selection, shot composition, and editing. Typically, a director has complete artistic control over all aspects of the movie, but it is not uncommon for the director to be bound by agreements with either a producer or a studio. In some large productions, a director will delegate less important scenes to a second unit. Widhout link to imdb*<br /><br />Example: Director of Pan's Labyrinth is **Guillermo del Toro***

    [imdb:genres] or [imdblive:genres]

One or more genres for current movie. <http://www.imdb.com/genre> IMDb list of all genres.*<br />Example: The Lord of the Rings: The Fellowship of the Ring has genres: **Action, Adventure, Fantasy***

    [imdb:plot] or [imdblive:plot]

A plot summary is a description of the story in a novel, film or other piece of storytelling. It is not a review and should not contain the opinions of the author. It should contain all the necessary information about the main characters and the unfolding drama to give a complete impression of the twists and turns in the plot, but without confusing the reader with unnecessary detail.*<br /><br />Example: Les quatre cents coups has plot: **Intensely touching story of a misunderstood young adolescent who left without attention, delves into a life of petty crime.***

    [imdb:poster]

Current movie poster image download automatic to your WordPress Media Library and display image as medium size from library.*<br />Example:<br />*<a href="http://www.imdb.com/title/tt0137523" title="Fight Club"><img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" height="200"/></a>

    [imdb:poster_nolink]

Current movie poster image download automatic to your WordPress Media Library and display image as medium size from library. Without link to imdb.*<br />Example:<br />*<img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" height="200"/>

    [imdb:posterRemote] or [imdblive:posterRemote]

Current movie poster image direct linked from server (remote). No locale savings!*<br />Example: <br />*<a href="http://www.imdb.com/title/tt0137523" title="Fight Club"><img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" height="200"/></a>

    [imdb:posterRemote_nolink] or [imdblive:posterRemote_nolink]

Current movie poster image direct linked from server (remote). No locale savings! Without link to imdb*<br />Example: <br />*<img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" height="200"/>

    [imdb:rating] or [imdblive:rating]

Rating scale from 1 to 10 with one decimal where 10 is best.<br /><br />*NOTE: This tag has language dependency, different WordPress language different output.<br /><br />Example: Pulp Fiction has rating **9.0** on english WordPress and **9,0** on swedish WordPress*

    [imdb:runtime] or [imdblive:runtime]

Runtime in minutes for current movie.<br /><br />*NOTE: This tag has language dependency, different WordPress language different output.<br /><br />Example: The very long movie Matrjoschka has runtime **5700** min long on english WordPress and **5 700** min long on swedish WordPress*

    [imdb:tagline] or [imdblive:tagline]

A tagline is a variant of a branding slogan typically used in marketing materials and advertising.<br /><br />*Example: Se7en has tagline **Long is the way, and hard, that out of hell leads up to light.***

    [imdb:tconst] or [imdblive:tconst]

Tconst/id for current movie.<br /><br />*Example: Se7en has Tconst **tt0114369***

    [imdb:title] or [imdblive:title]

Title for current moive with link to [imdb.com](http://www.imdb.com).<br /><br />*Example: Se7en has title **[Se7en](http://www.imdb.com/title/tt0114369/)***

    [imdb:title_nolink] or [imdblive:title_nolink]

Title for current moive without link to [imdb.com](http://www.imdb.com).<br /><br />*Example: Se7en has title **Se7en***

    [imdb:type] or [imdblive:type]

IMDb classifies titles under one of the following types: <ul><li>feature</li><li>short</li><li>documentary</li><li>video</li><li>tv_series</li><li>tv_special</li><li>video_game</li></ul>*Example: Game of Thrones has type **tv_series***

    [imdb:votes] or [imdblive:votes]

Number of votes from imdb members for the current movie.<br /><br />*NOTE: This tag has language dependency, different WordPress language different output.<br /><br />*Example: Game of Thrones has votes **307,685** on english WordPress and **307 685** on swedish WordPress*

    [imdb:writers] or [imdblive:writers]

A general term for someone who creates a written work, be it a novel, script, screenplay, or teleplay.<br /><br />*Example: Game of Thrones has tow writers **[David Benioff](http://www.imdb.com/name/nm1125275/) (creator), [D.B. Weiss](http://www.imdb.com/name/nm1888967/) (creator)***

    [imdb:writers_nolink] or [imdblive:writers_nolink]

A general term for someone who creates a written work, be it a novel, script, screenplay, or teleplay. Widhout link to imdb<br /><br />*Example: Game of Thrones has tow writers **David Benioff - (creator), D.B. Weiss - (creator)***

    [imdb:year] or [imdblive:year]

Publication year.<br /><br />Example: Pulp Fiction has publication year **1994**

= IMDb data copyright =
Limited non-commercial use of IMDb data is allowed, provided the following conditions are met:

1. You agree to all the terms of our [copyright/conditions](http://www.imdb.com/help/show_article?conditions) of use statement. Please also note that IMDb reserves the right to withdraw permission to use the data at any time at our discretion.
2. The data must be taken only direct from IMDb. You may not use data mining, robots, screen scraping, or similar online data gathering and extraction tools on our website.
3. The data can only be used for **personal and non-commercial** use and must not be altered/republished/resold/repurposed to create any kind of online/offline database of movie information (except for **individual personal** use). Please refer to the copyright/license information enclosed in each file for further instructions and limitations on allowed usage.
4. You must acknowledge the source of the data by including the following statement:
>Information courtesy of [The Internet Movie Database](http://www.imdb.com). Used with permission.

>**May I use a photo from your site for my web site or publication?**
Most of the photos on our site are licensed to us for our own use only. We do not have the authority to sublicense them to others. Photos on our site may be licensed directly from the license holders.

>For photos from "our Studio Friends," you'll need to contact the studio or production company. We recommend contacting the publicity department for current releases and the home video department for older films. For most movies, there is a "company credits" link near the top of the lefthand column on the film's listing. That will provide the name of the studio(s) and companies involved with making the film.

>Please note that many of our "posters" for older films are actually just scans of their video or DVD packaging. We cannot sublicense those images. Please contact the appropriate studio.

>For images from agencies like [WireImage](http://www.wireimage.com/), [Getty Images](http://www.gettyimages.com/) and [MPTV](http://www.mptvimages.com/), their names are linked on pages where we display their photos. Click on their names for more information about the agencies and links to their web sites where you can get licensing information.

>See: <http://pro.imdb.com/help/show_leaf?photoslicense>

== Screenshots ==
1. [IMDb site](http://www.imdb.com/)
2. Add New Post
3. Edit Post
4. View Post
5. Media Library
6. Edit Media

== Changelog ==
The code in WordPress plugin host is only production releases. Developers releases with unit-test and jenkins build config is hosted on [GitHub](https://github.com/HenrikRoos/imdb-markup-syntax)

= 2.5 =
1. Changes in [imdb:cast], [imdblive:cast]. Replace , to <br />. Thanks Aloysius69 for you feedback!
2. Changes in [imdb:cast_nolink], [imdblive:cast_nolink]. Show stars name without character name. Thanks reborn79 and bluecity for you feedback!
3. Add support for WordPress 4.2

= 2.4 =
1. Add support for WordPress 4.1

= 2.3 =
1. Add support for php 5.3. Must have php 5.3+.

= 2.2 =
1. New feature: Add support for [imdb:locale(...)] tag. Thanks *nexplissken*
2. Better support for WordPress Coding Standards for PHP_CodeSniffer
3. PHPUnit improvement for PhpStorm 8
4. Tested and supported for WordPress 4.0
5. Must have php 5.4+

= 2.1 =
1. New feature: Added support for one-off tags with embedded IDs **e.g. imdb-AboutAlex, imdblive-IntotheStorm, imdb-zaq12wsx, ...** [GitHub issue #8](https://github.com/HenrikRoos/imdb-markup-syntax/issues/8) **Thanks to [Daniel](https://github.com/danhunsaker)!**
2. Tested and supported for WordPress 3.9.1

= 2.0 =
1. New feature: Handle mulit tags in same content **e.g. imdb-a, imdb-b, ... imdb-z** [GitHub issue #2](https://github.com/HenrikRoos/imdb-markup-syntax/issues/2)
2. New feature: New tags *cast_nolink, directors_nolink, poster_nolink, posterRemote_nolink, title_nolink and writers_nolink*  with no links to imdb.com. **Thanks [williamxd3](http://profiles.wordpress.org/williamxd3)** [GitHub issue #4](https://github.com/HenrikRoos/imdb-markup-syntax/issues/4)
3. New feature: New tag *year* Year of publication of the movie. **Thanks [gauwain](http://profiles.wordpress.org/gauwain/)** [GitHub issue #5](https://github.com/HenrikRoos/imdb-markup-syntax/issues/5)
4. Update feature: Adding support to tags in post title *e.g [imdb:id(tt1951264)][imdb:title_nolink]* **Thanks [ray19](http://profiles.wordpress.org/ray19/)** [GitHub issue #3](https://github.com/HenrikRoos/imdb-markup-syntax/issues/3)
5. Tested and supported for WordPress 3.8

= 1.2 =
1. Tested and supported for WordPress 3.6
2. Improve error handling

= 1.1 =
1. Delete some options for a cURL transfer - use default instead.
2. Improve error handling for cURL transfer.
3. Add support for WordPress 3.5.2.

= 1.0 =
First stable release, tested from English WordPress (3.3, 3.3.1, 3.3.2, 3.3.3, 3.4, 3.4.1, 3.4.2, 3.5, 3.5.1), Svenska WordPress 3.5.1, Español WordPress 3.5.1 and ئۇيغۇرچە WordPress 3.5.1. Error messages in English and Swedish is supported.

= Easy to contribute and make changes =
If you want to be involved in developing this plug along, you are welcome to attend. The code is structured, well commented and have very high test coverage, so it is easy to contribute and make changes.
Of course you get a license for [PhpStorm 8](http://www.jetbrains.com/phpstorm/)!

== Frequently Asked Questions ==
see Support section
