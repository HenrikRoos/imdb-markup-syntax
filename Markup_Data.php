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
    public function getTconst()
    {
        if (!isset($this->_data->tconst)) {
            return false;
        }
        if (empty($this->_data->tconst)) {
            return false;
        }
        return $this->_data->tconst;
    }

    /**
     * Title for current moive
     * 
     * @return string|boolean if no title then false
     */
    public function getTitle()
    {
        if (!isset($this->_data->title)) {
            return false;
        }
        if (trim($this->_data->title) == false) {
            return false;
        }
        return $this->_data->title;
    }

    /**
     * IMDb classifies titles under one of the following types: feature,
     * short,documentary, video, tv_series, tv_special and video_game
     * 
     * @return string|boolean Current titles type <i>e.g. video or tv_series</i>
     */
    public function getType()
    {
        if (!isset($this->_data->type)) {
            return false;
        }
        if (trim($this->_data->type) == false) {
            return false;
        }
        return $this->_data->type;
    }

    /**
     * One or more genres for current movie. http://www.imdb.com/genre IMDb list of
     * all genres <i>e.g. Adventure, Action, Animation </i>
     * 
     * @return string|boolean List of genres in one string or false if no data
     */
    public function getGenres()
    {
        if (!isset($this->_data->genres)) {
            return false;
        }
        if (!is_array($this->_data->genres) || empty($this->_data->genres)) {
            return false;
        }
        return implode(", ", $this->_data->genres);
    }

    /**
     * The day when a movie is shipped to exhibitors by the distributor, it is deemed
     * to have been released for public viewing - there are no longer any studio
     * restrictions on who can see the movie. If no release date is given as used
     * publication year.
     * 
     * @return string|boolean In format 'Y-m-d' <i>e.g. 2013-12-24</i> or just 'Y'
     * <i>e.g. 2013</i> or false if no data is set.
     */
    public function getDate()
    {
        if (isset($this->_data->release_date->normal))
        {
            if (trim($this->_data->release_date->normal) != false) {
                return $this->_data->release_date->normal;
            }
        }
        if (isset($this->_data->year))
        {
            if (trim($this->_data->year) != false) {
                return $this->_data->year;
            }
        }
        return false;
    }

    /**
     * Runtime in minutes for current movie.
     * 
     * @return int|boolean Runtime in minutes or false if not data
     */
    public function getRuntime()
    {
        if (!isset($this->_data->runtime->time)) {
            return false;
        }
        if (empty($this->_data->runtime->time)) {
            return false;
        }
        return (int) round($this->_data->runtime->time / 60);
    }

    /**
     * Rating scale from 1 to 10 there 10 is best and with one decimal.
     * <i>e.g. 7.3</i>
     * 
     * @return float|boolean If no data then false
     */
    public function getRating()
    {
        if (!isset($this->_data->rating)) {
            return false;
        }
        if (empty($this->_data->rating)) {
            return false;
        }
        return $this->_data->rating;
    }

    /**
     * Number of votes from imdb members for current movie. <i>e.g. 3039</i>
     * 
     * @return int|boolean If no data then false.
     */
    public function getVotes()
    {
        if (!isset($this->_data->num_votes)) {
            return false;
        }
        if (empty($this->_data->num_votes)) {
            return false;
        }
        return $this->_data->num_votes;
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
    public function getPlot()
    {
        if (!isset($this->_data->plot->outline)) {
            return false;
        }
        if (trim($this->_data->plot->outline) == false) {
            return false;
        }
        return $this->_data->plot->outline;
    }

    /**
     * A tagline is a variant of a branding slogan typically used in marketing
     * materials and advertising.
     * <i>e.g. "Yippee Ki-Yay Mother Russia" (A Good Day to Die Hard)</i>
     * 
     * @return string|boolean If no tagline then false
     */
    public function getTagline()
    {
        if (!isset($this->_data->tagline)) {
            return false;
        }
        if (trim($this->_data->tagline) == false) {
            return false;
        }
        return $this->_data->tagline;
    }

    /**
     * A collective term for the actors appearing in a particular movie.
     * 
     * @return string|boolean List of actors as a one string or false if no data
     */
    public function getCast()
    {
        return isset($this->_data->cast_summary)
            ? $this->toSummaryString($this->_data->cast_summary, "\n")
            : false;
    }

    /**
     * A general term for someone who creates a written work, be it a novel, script,
     * screenplay, or teleplay.
     * 
     * @return string|boolean List of writers as a string
     */
    public function getWriters()
    {
        return isset($this->_data->writers_summary)
            ? $this->toSummaryString($this->_data->writers_summary, ", ")
            : false;
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
    public function getDirectors()
    {
        return isset($this->_data->directors_summary)
            ? $this->toSummaryString($this->_data->directors_summary, ", ")
            : false;
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
    public function getCertificate()
    {
        if (!isset($this->_data->certificate->certificate)) {
            return false;
        }
        if (trim($this->_data->certificate->certificate) == false) {
            return false;
        }
        return $this->_data->certificate->certificate;
    }

    /**
     * Current movie poster image
     * 
     * @return string URL to the image
     */
    public function getPoster()
    {
        if (!isset($this->_data->image->url)) {
            return false;
        }
        if (trim($this->_data->image->url) == false) {
            return false;
        }
        return $this->_data->image->url;
    }

    //<editor-fold defaultstate="collapsed" desc="Callables">
    /**
     * Convert data *_summary object to string contans persons as list separate by
     * specifde glue char(s).
     * 
     * @param array  $summary E.g $this->_data->directors_summary
     * @param string $glue    One or more char as separat between persons in the list
     * 
     * @return boolean|string contans all persons or false if no data
     */
    protected function toSummaryString($summary, $glue)
    {
        if (!is_array($summary)) {
            return false;
        }
        $summaryList = $this->toPersonsList($summary);
        if ($summaryList === false) {
            return false;
        }
        return implode($glue, $summaryList);
    }

    /**
     * Convert json objects persion to array contans string format for the
     * persons
     * 
     * @param array $personsObj list of persions objects
     * 
     * @return array|boolean list that persion is a string markup
     */
    protected function toPersonsList(array $personsObj)
    {
        $named = array_map(array($this, "toPersonString"), $personsObj);
        $named_summary = array_filter($named, array($this, "isNotEmpty"));
        if (count($named_summary) == 0) {
            return false;
        }
        return $named_summary;
    }

    /**
     * Extract name like data into a string e.g.
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
     * @param stdClass $person object where minimun name object is set
     * 
     * @return string concat data into a string
     */
    protected function toPersonString(stdClass $person)
    {
        $resultArr = array();
        $props = get_object_vars($person);
        if (isset($props["image"])) {
            unset($props["image"]);
        }
        if ((isset($person->name->name)) && is_object($person->name)) {
            array_push($resultArr, $this->toNameString($person->name));
            unset($props["name"]);
        }
        $result = array_merge($resultArr, $props);
        $resultStr = implode(" ", $result);
        return trim($resultStr);
    }

    /**
     * Convert name objekt into string
     * 
     * @param stdClass $nameObj An array like
     * <code>
     * [name] => stdClass Object
     *     (
     *        [nconst] => nm0254645
     *        [name] => Ted Elliott
     *     )
     * </code>
     * 
     * @return string like <i>
     * <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)</i>
     */
    protected function toNameString(stdClass $nameObj)
    {
        return isset($nameObj->nconst)
            ? "<a href=\"http://www.imdb.com/name/{$nameObj->nconst}\">"
            . $nameObj->name . "</a>"
            : $nameObj->name;
    }

    /**
     * Check if a string is empty or not
     * 
     * @param string $value A string
     * 
     * @return boolean true if is not empty false if is empty
     */
    protected function isNotEmpty($value)
    {
        return !empty($value);
    }

    //</editor-fold>
}

?>
