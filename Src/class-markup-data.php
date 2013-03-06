<?php

namespace IMDb_Markup_Syntax;

use stdClass;

/**
 * Markup data tags from INDb data result. Most popular tag in imdb result has a function in this class.
 * @author Henrik Roos <henrik at afternoon.se>
 * @package imdb-markup-syntax
 */
class Markup_Data {

    /** @var stdClass imdb data result */
    private $data;

    /**
     * Create a instans of this class
     * @param stdClass $data IMDb data json class
     */
    public function __construct(stdClass $data) {
        $this->data = $data;
    }

    /**
     * id (tconst) for current movie ex tt0137523
     * @return string|boolean if no id then FALSE
     */
    public function id() {
        //TODO some code please
    }

    /**
     * Title for current moive
     * @return string|boolean if no title then FALSE
     */
    public function title() {
        //TODO some code please
    }

    /**
     * IMDb classifies titles under one of the following types: feature, short, documentary, video, tv_series,
     * tv_special and video_game.
     * @return string|boolean current titles type ex video or tv_series
     */
    public function type() {
        //TODO some code please
    }

    /**
     * One or more genres for current movie. @link http://www.imdb.com/genre IMDb list of all genres
     * ex Adventure, Action, Animation etc
     * @return string|boolean list of genres in one string or FALSE if no data
     */
    public function genres() {
        //TODO some code please
    }

    /**
     * The day when a movie is shipped to exhibitors by the distributor, it is deemed to have been released
     * for public viewing - there are no longer any studio restrictions on who can see the movie.
     * @return string|boolean in format 'Y-m-d' ex 2013-12-24 or FALSE if no date
     */
    public function release_date() {
        //TODO some code please
    }

    /**
     * Runtime in minutes for current movie.
     * @return int|boolean Runtime in minutes or FALSE if not data
     */
    public function runtime() {
        //TODO some code please
    }

    /**
     * Rating scale from 1 to 10 there 10 is best and with one decimal. ex 7.3
     * @return float|boolean if no rating then FALSE
     */
    public function rating() {
        //TODO some code please
    }

    /**
     * Number of votes from imdb members for current movie. ex 3039
     * @return int|boolean if no data then FALSE.
     */
    public function votes() {
        //TODO some code please
    }

    /**
     * A plot summary is a description of the story in a novel, film or other piece of storytelling.
     * It is not a review and should not contain the opinions of the author. It should contain all the
     * necessary information about the main characters and the unfolding drama to give a complete impression
     * of the twists and turns in the plot, but without confusing the reader with unnecessary detail.
     * @return string|boolean if no plot then FALSE
     */
    public function plot() {
        //TODO some code please
    }

    /**
     * A tagline is a variant of a branding slogan typically used in marketing materials and advertising.
     * ex "Yippee Ki-Yay Mother Russia" (Movie: "A Good Day to Die Hard")
     * @return string|boolean if no tagline then FALSE
     */
    public function tagline() {
        //TODO some code please
    }

    /**
     * A collective term for the actors appearing in a particular movie.
     * @return string|boolean list of actors as a one string or FALSE if no data
     */
    public function cast() {
        //TODO some code please
    }

    /**
     * A general term for someone who creates a written work, be it a novel, script, screenplay, or teleplay.
     * @return string|boolean list of writers as a string
     */
    public function writers() {
        if (isset($this->data->writers_summary) && is_array($this->data->writers_summary)) {
            $named = array_filter($this->data->writers_summary, function($writer) {
                        return isset($writer->name->name);
                    });
            $named_summary = array_map(array($this, "writer"), $named);
            return implode(", ", $named_summary);
        }
        return FALSE;
    }

    /**
     * The principal creative artist on a movie set. A director is usually (but not always) the driving
     * artistic source behind the filming process, and communicates to actors the way that he/she would like a
     * particular scene played. A director's duties might also include casting, script editing, shot
     * selection, shot composition, and editing. Typically, a director has complete artistic control over all
     * aspects of the movie, but it is not uncommon for the director to be bound by agreements with either a
     * producer or a studio. In some large productions, a director will delegate less important scenes to a
     * second unit.
     * @return string|boolean list of directors as a one string or FALSE if no data
     */
    public function directors() {
        //TODO some code please
    }

    /**
     * Various countries or regions have film classification boards for reviewing movies and rating their
     * content in terms of its suitability for particular audiences. For many countries, movies are required
     * to be advertised as having a particular "certificate" or "rating", forewarning audiences of possible
     * "objectionable content". The nature of this "objectionable content" is determined mainly by
     * contemporary national, social, religious, and political standards. The usual criteria which determine a
     * film's certificate are violence and sexuality, with "mature" (adult) situations and especially
     * blasphemy and political issues often being considered more important outside the Western world. This is
     * by no means a hard and fast rule; see the Hays Production Code for an example. In some cases, a film
     * classification board exhibits censorship by demanding changes be made to a movie in order to receive a
     * certain rating. As many movies are targetted at a particular age group, studios must balance the
     * content of their films against the demands of the classification board. Negotiations are common;
     * studios agree to make certain changes to films in order to receive the required rating. The IMDb uses
     * the term "Certificate" as opposed to "Rating" to avoid confusion with "ratings" meaning the opinions
     * of critics. @link http://www.filmratings.com Classification and Rating Administration (CARA)
     * @return string|boolean code ex G, PG, PG-13, R, NC-17 or FALSE if no data
     */
    public function certificate() {
        //TODO some code please
    }

    // TODO poster document
    public function poster() {
        //TODO some code please
    }

    // <editor-fold defaultstate="collapsed" desc="callables">
    /**
     * INPUT:
     * stdClass Object
     * (
     *     [name] => stdClass Object
     *     (
     *        [nconst] => nm0254645
     *        [name] => Ted Elliott
     *     )
     *     [attr] => (characters)
     * )
     * OUTPUT: <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)
     * @param stdClass $writer array item from writers_summary
     * @return string ex <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)
     */
    protected function writer(stdClass $writer) {
        $res = isset($writer->name->nconst) ? "<a href=\"http://www.imdb.com/name/{$writer->name->nconst}\">{$writer->name->name}</a>" : $writer->name->name;
        if (isset($writer->attr)) {
            $res .= " " . $writer->attr;
        }
        return $res;
    }

    // </editor-fold>
}

?>
