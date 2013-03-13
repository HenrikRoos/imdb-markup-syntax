<?php

/**
 * Markup data tags from INDb data result. Most popular tag in imdb result has a
 * function in this class
 * 
 * PHP version 5
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax;

use stdClass;

/**
 * Markup data tags from INDb data result. Most popular tag in imdb result has a
 * function in this class
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Markup_Data
{

    /** @var stdClass imdb data result */
    private $_data;

    /**
     * Create a instans of this class
     * 
     * @param stdClass $data IMDb data json class
     */
    public function __construct(stdClass $data)
    {
        $this->_data = $data;
    }

    /**
     * Tconst for current movie <i>e.g. tt0137523</i>
     * 
     * @return string|boolean if no data then false
     */
    public function tconst()
    {
        //TODO some code please
    }

    /**
     * Title for current moive
     * 
     * @return string|boolean if no title then false
     */
    public function title()
    {
        //TODO some code please
    }

    /**
     * IMDb classifies titles under one of the following types: feature,
     * short,documentary, video, tv_series, tv_special and video_game
     * 
     * @return string|boolean Current titles type <i>e.g. video or tv_series</i>
     */
    public function type()
    {
        //TODO some code please
    }

    /**
     * One or more genres for current movie. http://www.imdb.com/genre IMDb list of
     * all genres <i>e.g. Adventure, Action, Animation </i>
     * 
     * @return string|boolean List of genres in one string or false if no data
     */
    public function genres()
    {
        //TODO some code please
    }

    /**
     * The day when a movie is shipped to exhibitors by the distributor, it is deemed
     * to have been released for public viewing - there are no longer any studio
     * restrictions on who can see the movie
     * 
     * @return string|boolean In format 'Y-m-d' <i>e.g. 2013-12-24</i> or false if no
     * data
     */
    public function releaseDate()
    {
        //TODO some code please
    }

    /**
     * Runtime in minutes for current movie.
     * 
     * @return int|boolean Runtime in minutes or false if not data
     */
    public function runtime()
    {
        //TODO some code please
    }

    /**
     * Rating scale from 1 to 10 there 10 is best and with one decimal.
     * <i>e.g. 7.3</i>
     * 
     * @return float|boolean If no data then false
     */
    public function rating()
    {
        //TODO some code please
    }

    /**
     * Number of votes from imdb members for current movie. <i>e.g. 3039</i>
     * 
     * @return int|boolean If no data then false.
     */
    public function votes()
    {
        //TODO some code please
    }

    /**
     * A plot summary is a description of the story in a novel, film or other piece
     * of storytelling. It is not a review and should not contain the opinions of the
     * author. It should contain all the necessary information about the main
     * characters and the unfolding drama to give a complete impression of the twists
     * and turns in the plot, but without confusing the reader with unnecessary
     * detail.
     * 
     * @return string|boolean If no data then false
     */
    public function plot()
    {
        //TODO some code please
    }

    /**
     * A tagline is a variant of a branding slogan typically used in marketing
     * materials and advertising.
     * <i>e.g. "Yippee Ki-Yay Mother Russia" (A Good Day to Die Hard)</i>
     * 
     * @return string|boolean If no tagline then false
     */
    public function tagline()
    {
        //TODO some code please
    }

    /**
     * A collective term for the actors appearing in a particular movie.
     * 
     * @return string|boolean List of actors as a one string or false if no data
     */
    public function cast()
    {
        //TODO some code please
    }

    /**
     * A general term for someone who creates a written work, be it a novel, script,
     * screenplay, or teleplay.
     * 
     * @return string|boolean List of writers as a string
     */
    public function writers()
    {
        if (isset($this->_data->writers_summary)
            && is_array($this->_data->writers_summary)
        ) {
            $named = array_filter(
                $this->_data->writers_summary, array($this, "hasName")
            );
            $named_summary = array_map(array($this, "writer"), $named);
            return implode(", ", $named_summary);
        }
        return false;
    }

    /**
     * The principal creative artist on a movie set. A director is usually (but not
     * always) the driving artistic source behind the filming process, and
     * communicates to actors the way that he/she would like a particular scene
     * played. A director's duties might also include casting, script editing, shot
     * selection, shot composition, and editing. Typically, a director has complete
     * artistic control over all aspects of the movie, but it is not uncommon for the
     * director to be bound by agreements with either a producer or a studio. In some
     * large productions, a director will delegate less important scenes to a second
     * unit.
     * 
     * @return string|boolean List of directors as a one string or false if no data
     */
    public function directors()
    {
        //TODO some code please
    }

    /**
     * Various countries or regions have film classification boards for reviewing
     * movies and rating their content in terms of its suitability for particular
     * audiences. For many countries, movies are required to be advertised as having
     * a particular "certificate" or "rating", forewarning audiences of possible
     * "objectionable content". The nature of this "objectionable content" is
     * determined mainly by contemporary national, social, religious, and political
     * standards. The usual criteria which determine a film's certificate are
     * violence and sexuality, with "mature" (adult) situations and especially
     * blasphemy and political issues often being considered more important outside
     * the Western world. This is by no means a hard and fast rule; see the Hays
     * Production Code for an example. In some cases, a film classification board
     * exhibits censorship by demanding changes be made to a movie in order to
     * receive a certain rating. As many movies are targetted at a particular age
     * group, studios must balance the content of their films against the demands of
     * the classification board. Negotiations are common; studios agree to make
     * certain changes to films in order to receive the required rating. The IMDb
     * uses the term "Certificate" as opposed to "Rating" to avoid confusion with
     * "ratings" meaning the opinions of critics. http://www.filmratings.com
     * Classification and Rating Administration (CARA)
     * 
     * @return string|boolean Code <i>e.g G, PG, PG-13, R, NC-17</i> or false if no
     * data
     */
    public function certificate()
    {
        //TODO some code please
    }

    /**
     * Current movie poster image
     * 
     * @return string URL to the image
     */
    public function poster()
    {
        //TODO some code please
    }

    // <editor-fold defaultstate="collapsed" desc="callables">
    /**
     * <b>input</b>
     * <code>
     * stdClass Object
     * (
     *     [name] => stdClass Object
     *     (
     *        [nconst] => nm0254645
     *        [name] => Ted Elliott
     *     )
     *     [attr] => (characters)
     * )
     * </code>
     * <b>output</b>
     * <code>
     * <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)
     * </code>
     * 
     * @param stdClass $writer Array item from writers_summary
     * 
     * @return string e.g.
     * <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)
     */
    protected function writer(stdClass $writer)
    {
        $res = isset($writer->name->nconst)
            ? "<a href=\"http://www.imdb.com/name/{$writer->name->nconst}\">{$writer->name->name}</a>"
            : $writer->name->name;
        if (isset($writer->attr)) {
            $res .= " " . $writer->attr;
        }
        return $res;
    }

    /**
     * Check if writer name is set
     * 
     * @param stdClass $writer array item from writers_summary
     * 
     * @return boolean True if name is set false if no name is set
     */
    protected function hasName(stdClass $writer)
    {
        return isset($writer->name->name);
    }

}

// </editor-fold>
?>
