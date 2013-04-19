# Description
This plugin makes it possible to insert movie data in your blog from the IMDb Web Service which is the same datasource that IMDb:s Mobile apps is using [IMDb Mobile Applications](http://app.imdb.com).

## How to use the plugin on your blog
You have watched a movie and want to post a comment on it on your blog. With a few simple steps you can use this plugin to extract movie facts and display it on your blog which will complement your writing with professional and exact facts about the movie. 

1. Go to [IMDb](http://imdb.com/) and search the movie you want to write about.
2. Copy the movie id tag shown in the url, for example: *http://www.imdb.com/title/**tt0081505*** i.e. **tt0081505** This tag will help you to insert facts from the movie that will be displayed and highlighted when you are writing the movie title, name of the actors, the directors and so on. 
3. For example: *[imdb:id(tt0081505)] Yesterday I saw an old movie, [imdb:title]. I really liked both the [imdb:directors] and the movie*
4. Your text will be displayed as: *Yesterday I saw an old movie, The Shining. I really liked both the [Stanley Kubrick](http://www.imdb.com/name/nm0000040) and the movie*
5. You can also insert statistics such as the number of people who has comment on the movie at IMDb´s website and this will be automatically updated whenever your own blog page is refreshed.

## Example
In post *edit* mode you write:

```
[imdb:id(tt0110912)]
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros non dui porta tincidunt. Nulla ut mi eget justo ultrices auctor sed in lacus.

Title: [imdb:title]
Release Date: [imdb:date]

Vivamus id sem felis. Donec consequat urna et sapien gravida bibendum sed ut orci. Donec eu nibh leo. Etiam hendrerit justo eget est vehicula eu ornare dolor vulputate. 
```
**After** you save it is transform to:

```
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum venenatis eros non dui porta tincidunt. Nulla ut mi eget justo ultrices auctor sed in lacus.

Title: Pulp Fiction
Release Date: 1994-10-14

Vivamus id sem felis. Donec consequat urna et sapien gravida bibendum sed ut orci. Donec eu nibh leo. Etiam hendrerit justo eget est vehicula eu ornare dolor vulputate. 
```
# Markup Syntax
Tag                                          | Description
-------------------------------------------- | ---------------
`[imdb:id(tt0000000)]`                       | Set the current movie. All tags starting with `[imdb` use this id. This ID disappearance when you **save** the post into your database.
`[imdblive:id(tt0000000)]`                   | Set the current movie. All tags starting with `[imdblive` use this id. This ID disappearance when you **read** the post from your database.
`[imdb:cast]` `[imdblive:cast]`              | A list of main actors. *<br />Example:<br />[Elijah Wood](http://www.imdb.com/name/nm0000704) Frodo Baggins<br />[Ian McKellen](http://www.imdb.com/name/nm0005212) Gandalf the Grey<br />[Orlando Bloom](http://www.imdb.com/name/nm0089217) Legolas Greenleaf<br />[Sean Bean](http://www.imdb.com/name/nm0000293) Boromir*
`[imdb:certificate]` `[imdblive:certificate]`| Various countries or regions have film classification boards for reviewing movies and rating their content in terms of its suitability for particular audiences. For many countries, movies are required to be advertised as having a particular "certificate" or "rating", forewarning audiences of possible "objectionable content". The nature of this "objectionable content" is determined mainly by contemporary national, social, religious, and political standards. The usual criteria which determine a film's certificate are violence and sexuality, with "mature" (adult) situations and especially blasphemy and political issues often being considered more important outside the Western world. This is by no means a hard and fast rule; see the Hays Production Code for an example. In some cases, a film classification board exhibits censorship by demanding changes be made to a movie in order to receive a certain rating. As many movies are targetted at a particular age group, studios must balance the content of their films against the demands of the classification board. Negotiations are common; studios agree to make certain changes to films in order to receive the required rating. The IMDb uses the term "Certificate" as opposed to "Rating" to avoid confusion with "ratings" meaning the opinions of critics. <http://www.filmratings.com> Classification and Rating Administration (CARA)*<br /><br />Example: Fight Club has certificate **PG** on english WordPress and **15** on swedish WordPress*
`[imdb:date]` `[imdblive:date]`              | The day when a movie is shipped to exhibitors by the distributor, it is deemed to have been released for public viewing - there are no longer any studio restrictions on who can see the movie. If no release date is given as used publication year.*<br /><br />Example: The Dark Knight has release date **2008-07-18** english WordPress and **2008-07-25** on swedish WordPress*
`[imdb:directors]` `[imdblive:directors]`    | The principal creative artist on a movie set. A director is usually (but not always) the driving artistic source behind the filming process, and communicates to actors the way that he/she would like a particular scene played. A director's duties might also include casting, script editing, shot selection, shot composition, and editing. Typically, a director has complete artistic control over all aspects of the movie, but it is not uncommon for the director to be bound by agreements with either a producer or a studio. In some large productions, a director will delegate less important scenes to a second unit.*<br /><br />Example: Director of Pan's Labyrinth is **[Guillermo del Toro](http://www.imdb.com/name/nm0868219)***
`[imdb:genres]` `[imdblive:genres]`          | One or more genres for current movie. <http://www.imdb.com/genre> IMDb list of all genres.*<br />Example: The Lord of the Rings: The Fellowship of the Ring has genres: **Action, Adventure, Fantasy***
`[imdb:plot]` `[imdblive:plot]`              | A plot summary is a description of the story in a novel, film or other piece of storytelling. It is not a review and should not contain the opinions of the author. It should contain all the necessary information about the main characters and the unfolding drama to give a complete impression of the twists and turns in the plot, but without confusing the reader with unnecessary detail.*<br /><br />Example: Les quatre cents coups has plot: **Intensely touching story of a misunderstood young adolescent who left without attention, delves into a life of petty crime.***
`[imdb:poster]` `[imdblive:poster]`          | Current movie poster image.*<br />Example: <http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg><br />*<a href="http://www.imdb.com/title/tt0137523" title="Fight Club"><img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" height="200"/></a>


# IMDb data copyright
Limited non-commercial use of IMDb data is allowed, provided the following conditions are met:

1. You agree to all the terms of our [copyright/conditions](http://www.imdb.com/help/show_article?conditions) of use statement. Please also note that IMDb reserves the right to withdraw permission to use the data at any time at our discretion.
2. The data must be taken only direct from IMDb. You may not use data mining, robots, screen scraping, or similar online data gathering and extraction tools on our website.
3. The data can only be used for **personal and non-commercial** use and must not be altered/republished/resold/repurposed to create any kind of online/offline database of movie information (except for **individual personal** use). Please refer to the copyright/license information enclosed in each file for further instructions and limitations on allowed usage.
4. You must acknowledge the source of the data by including the following statement:
>Information courtesy of [The Internet Movie Database](http://www.imdb.com). Used with permission.

>###May I use a photo from your site for my web site or publication?
Most of the photos on our site are licensed to us for our own use only. We do not have the authority to sublicense them to others. Photos on our site may be licensed directly from the license holders.

>For photos from "our Studio Friends," you'll need to contact the studio or production company. We recommend contacting the publicity department for current releases and the home video department for older films. For most movies, there is a "company credits" link near the top of the lefthand column on the film's listing. That will provide the name of the studio(s) and companies involved with making the film.

>Please note that many of our "posters" for older films are actually just scans of their video or DVD packaging. We cannot sublicense those images. Please contact the appropriate studio.

>For images from agencies like [WireImage](http://www.wireimage.com/), [Getty Images](http://www.gettyimages.com/) and [MPTV](http://www.mptvimages.com/), their names are linked on pages where we display their photos. Click on their names for more information about the agencies and links to their web sites where you can get licensing information.

>See: <http://pro.imdb.com/help/show_leaf?photoslicense>


# Screenshots
xxx

# Changelog
## 1.0
xxx
