<?php
/**
 * Markup data tags from IMDb data result. Most popular tag in imdb result has a
 * function in this class
 *
 * PHP version 5
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

require_once 'media-library-handler.php';

/**
 * Markup data tags from IMDb data result. Most popular tag in imdb result has a
 * function in this class
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Markup_Data {

	/** @var int Current Blog Post ID, defualt is 0 */
	public $post_id;
	/** @var string Localization for data, standard RFC 4646 */
	public $locale;
	/** @var string Core syntax in tags. *e.g. prefix = imdb => [imdb:date]
	 * prefix = abc => [abc:date]* */
	protected $prefix;
	/** @var stdClass imdb data result */
	private $_data;

	/**
	 * Create an instans of this class
	 *
	 * @param stdClass $data IMDb data json class
	 * @param int $post_id Current Blog Post ID
	 * @param string $locale Localization for data, standard RFC 4646
	 * @param string $prefix Core syntax in tags. *e.g. prefix = imdb =>
	 * [imdb:date] prefix = abc => [abc:date]
	 */
	public function __construct(
		stdClass $data,
		$post_id = 0,
		$locale = '',
		$prefix = 'imdb'
	) {
		$this->_data   = $data;
		$this->post_id = $post_id;
		$this->locale  = $locale;
		$this->prefix  = $prefix;
	}

	/**
	 * A collective term for the actors appearing in a particular movie.
	 *
	 * @return string|boolean List of actors as a one string or false if no data
	 */
	public function get_cast() {
		return $this->to_summary_string( 'cast_summary', '<br />', true, true );
	}


	/**
	 * Convert data *_summary object to string contains persons as list separate by specified glue char(s).
	 *
	 * @param string $summary E.g <i>directors_summary</i> in $this->_data->directors_summary
	 * @param string $glue One or more char as separate between persons in the list default is <i>", "</i>
	 * @param boolean $link true = name with url (default) else just name
     * @param boolean $attr true = name with artist name or title. Default if false
	 *
	 * @return boolean|string contains all persons or false if no data
	 */
	protected function to_summary_string( $summary, $glue = ', ', $link = true, $attr = false ) {
		if ( isset( $this->_data->$summary ) && ! empty( $this->_data->$summary ) && is_array( $this->_data->$summary )
		) {
			$summaryList = $this->to_persons_list( $this->_data->$summary, $link, $attr );
			if ( ! empty( $summaryList ) ) {
				return implode( $glue, $summaryList );
			}
		}

		return false;
	}

	/**
	 * Convert json objects persons to array contains string format for the persons
	 *
	 * @param array $personsObj list of persons objects
	 * @param boolean $link true = name with url (default) else just name
     * @param boolean $attr true = name with artist name or title.
	 *
	 * @return array|boolean list that persons is a string markup
	 */
	protected function to_persons_list( array $personsObj, $link, $attr ) {
		$named = array();
		foreach ( $personsObj as $nameObj ) {
			$named[] = $this->to_person_string( $nameObj, $link, $attr );
		}
		$named_summary = array_filter( $named, array( $this, 'is_not_empty' ) );

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
	 * @param stdClass $person object where minimum name object is set
	 * @param boolean $link true = name with url (default) else just name
     * @param boolean $attr true = name with artist name or title.
	 *
	 * @return string concat data into a string
	 */
	protected function to_person_string( stdClass $person, $link, $attr ) {
        $resultArr = array();
        $props     = get_object_vars( $person );
		if ( ( isset( $person->name->name ) ) && is_object( $person->name ) ) {
            array_push( $resultArr, $this->to_name_string( $person->name, $link ) );
		}
        if ($attr) {
            unset( $props['name'] );
            $result    = array_merge( $resultArr, $props );
            $glue      = $link ? ' ' : ' - ';
            $resultStr = implode( $glue, $result );
        } else {
            $resultStr = implode( '' , $resultArr );
        }

        return trim( $resultStr );
	}

	/**
	 * Convert name object into string
	 *
	 * @param stdClass $nameObj An array like
	 * <code>
	 * [name] => stdClass Object
	 *     (
	 *        [nconst] => nm0254645
	 *        [name] => Ted Elliott
	 *     )
	 * </code>
	 * @param boolean $link true = name with url (defualt) else just name
	 *
	 * @return string like <i>
	 * <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)</i>
	 */
	protected function to_name_string( stdClass $nameObj, $link ) {
		return ( isset( $nameObj->nconst ) && $link === true )
			? '<a href="http://www.imdb.com/name/' . $nameObj->nconst . '">' . $nameObj->name . '</a>'
			: $nameObj->name;
	}

	/**
	 * A collective term for the actors appearing in a particular movie.
	 *
	 * @return string|boolean List of actors as a one string or false if no data
	 * without links to imdb
	 */
	public function get_cast_nolink() {
		return $this->to_summary_string( 'cast_summary', ', ', false );
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
	public function get_certificate() {
		return $this->get_value_value( 'certificate', 'certificate' );
	}

	/**
	 * Help function check and get value for specifide objekt
	 *
	 * @param string $na1 name of the first function
	 * @param string $na2 name of the second function
	 *
	 * @return int|float|string|boolean data value or false if value not set or empty
	 */
	public function get_value_value( $na1, $na2 ) {
		return ( isset( $this->_data->$na1->$na2 ) && ! empty( $this->_data->$na1->$na2 ) )
			? $this->_data->$na1->$na2
			: false;
	}

	/**
	 * The year when a movie is shipped to exhibitors by the distributor.
	 *
	 * @return string|boolean Year e.g 1999
	 */
	public function get_year() {
		return $this->get_value( 'year' );
	}

	/**
	 * Help function check and get value for specifide objekt
	 *
	 * @param string $name name of the first function
	 *
	 * @return int|float|string|boolean data value or false if value not set or empty
	 */
	public function get_value( $name ) {
		return ( isset( $this->_data->$name ) && ! empty( $this->_data->$name ) )
			? $this->_data->$name
			: false;
	}

	/**
	 * The day when a movie is shipped to exhibitors by the distributor, it is deemed
	 * to have been released for public viewing - there are no longer any studio
	 * restrictions on who can see the movie. If no release date is given as used
	 * publication year.
	 *
	 * @return string|boolean Preferred date representation based on locale, without
	 * the time <i>e.g. 2013-12-24 or 12/24/13</i> or just 'Y'
	 * <i>e.g. 2013</i> or false if no data is set.
	 */
	public function get_date() {
		if ( isset( $this->_data->release_date->normal ) ) {
			if ( ! empty( $this->_data->release_date->normal ) ) {
				setlocale( LC_TIME, $this->locale );
				$timestamp = strtotime( $this->_data->release_date->normal );
				$datetime  = strftime( '%c', $timestamp ); //Tue Feb 5 00:45:10 2009
				$time      = strftime( '%X', $timestamp ); //00:45:10
				$date      = str_replace( ' ' . $time, '', $datetime ); //Tue Feb 5 2009
				return $date;
			}
		}
		if ( isset( $this->_data->year ) ) {
			if ( ! empty( $this->_data->year ) ) {
				return $this->_data->year;
			}
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
	public function get_directors() {
		return $this->to_summary_string( 'directors_summary' );
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
	 * without links to imdb
	 */
	public function get_directors_nolink() {
		return $this->to_summary_string( 'directors_summary', ', ', false );
	}

	/**
	 * One or more genres for current movie. http://www.imdb.com/genre IMDb list of
	 * all genres <i>e.g. Adventure, Action, Animation </i>
	 *
	 * @return string|boolean List of genres in one string or false if no data
	 */
	public function get_genres() {
		$genres = $this->get_value( 'genres' );

		return is_array( $genres )
			? implode( ', ', $genres )
			: false;
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
	public function get_plot() {
		return $this->get_value_value( 'plot', 'outline' );
	}

	/**
	 * Current movie poster image as html widh link to the movie and alt text.
	 * Poster images donwload automatic and store in Media Lib.
	 *
	 * @return string|boolean URL to the image or false if no data
	 */
	public function get_poster() {
		$remote_url = $this->get_value_value( 'image', 'url' );
		if ( empty( $this->post_id ) || $remote_url === false ) {
			return false;
		}
		$filename = $this->get_value( 'tconst' );
		$lib      = new Media_Library_Handler( $this->post_id, $remote_url, $filename );
		$href     = 'http://www.imdb.com/title/' . $this->get_value( 'tconst' ) . '/';
		$title    = $this->get_value( 'title' );

		return $lib->get_html( $href, $title );
	}

	/**
	 * Current movie poster image as html widhout link to the movie.
	 * Poster images donwload automatic and store in Media Lib.
	 *
	 * @return string|boolean the image or false if no data, without url to imdb
	 */
	public function get_poster_nolink() {
		$remote_url = $this->get_value_value( 'image', 'url' );
		if ( empty( $this->post_id ) || $remote_url === false ) {
			return false;
		}
		$filename = $this->get_value( 'tconst' );
		$lib      = new Media_Library_Handler( $this->post_id, $remote_url, $filename );
		$title    = $this->get_value( 'title' );

		return $lib->get_html( null, $title );
	}

	/**
	 * Current movie poster image with no local storeage. Use remote location.
	 *
	 * @return string
	 */
	public function get_posterremote() {
		$img = $this->get_posterremote_nolink();
		if ( $img === false ) {
			return false;
		}
		$href = 'http://www.imdb.com/title/' . $this->get_value( 'tconst' ) . '/';

		return '<a href="' . $href . '">' . $img . '</a>';
	}

	/**
	 * Current movie poster image with no local storeage. Use remote location.
	 * without link to imdb
	 *
	 * @return string
	 */
	public function get_posterremote_nolink() {
		if ( $this->get_value_value( 'image', 'url' ) === false ) {
			return false;
		}
		$src  = ' src="' . $this->get_value_value( 'image', 'url' ) . '"';
		$alt  = $this->get_value( 'title' )
			? ' alt="' . $this->get_value( 'title' ) . '"'
			: '';
		$size = ' width="200"';
		$css  = ' class="alignnone"';

		return '<img' . $src . $alt . $size . $css . '/>';
	}

	/**
	 * Rating scale from 1 to 10 where 10 is best and with one decimal.
	 * <i>e.g. 7.3</i>
	 *
	 * @return float|boolean If no data then false
	 */
	public function get_rating() {
		return $this->number_format_locale( $this->get_value( 'rating' ), 1 );
	}

	/**
	 * Convert number to format number by current format roules.
	 *
	 * @param int $number The number you will format
	 * @param int $decimals Number of decimals, defualt is 0
	 *
	 * @return string|boolean Format number for current locale or false if number
	 * is not a numeric.
	 */
	protected function number_format_locale( $number, $decimals = 0 ) {
		if ( ! setlocale( LC_NUMERIC, $this->locale ) ) {
			setlocale( LC_NUMERIC, '' );
		}
		if ( is_numeric( $number ) ) {
			$locale = localeconv();
			$num    = number_format(
				$number, $decimals, $locale['decimal_point'],
				$locale['thousands_sep']
			);

			return $num;
		}

		return false;
	}

	/**
	 * Runtime in minutes for current movie.
	 *
	 * @return string|boolean Runtime in minutes or false if not data
	 */
	public function get_runtime() {
		$runtime = $this->get_value_value( 'runtime', 'time' );

		return $runtime
			? $this->number_format_locale( $runtime / 60 )
			: false;
	}

	/**
	 * A tagline is a variant of a branding slogan typically used in marketing
	 * materials and advertising.
	 * <i>e.g. "Yippee Ki-Yay Mother Russia" (A Good Day to Die Hard)</i>
	 *
	 * @return string|boolean If no tagline then false
	 */
	public function get_tagline() {
		return $this->get_value( 'tagline' );
	}

	/**
	 * Tconst for current movie <i>e.g. tt0137523</i>
	 *
	 * @return string|boolean if no data then false
	 */
	public function get_tconst() {
		return $this->get_value( 'tconst' );
	}

	/**
	 * Title for current moive
	 *
	 * @return string|boolean if no title then false
	 */
	public function get_title() {
		$title = $this->get_value( 'title' );
		if ( empty( $title ) ) {
			return false;
		}

		return '<a href="http://www.imdb.com/title/' . $this->get_value( 'tconst' ) . '/">' . $title . '</a>';
	}

	/**
	 * Title for current moive without href link to imdb
	 *
	 * @return string|boolean if no title then false
	 */
	public function get_title_nolink() {
		$title = $this->get_value( 'title' );
		if ( empty( $title ) ) {
			return false;
		}

		return $title;
	}

	/**
	 * IMDb classifies titles under one of the following types: feature,
	 * short,documentary, video, tv_series, tv_special and video_game
	 *
	 * @return string|boolean Current titles type <i>e.g. video or tv_series</i>
	 */
	public function get_type() {
		return $this->get_value( 'type' );
	}

	/**
	 * Number of votes from imdb members for the current movie. <i>e.g. 3039</i>
	 *
	 * @return string|boolean If no data then false.
	 */
	public function get_votes() {
		return $this->number_format_locale( $this->get_value( 'num_votes' ) );
	}

	/**
	 * A general term for someone who creates a written work, be it a novel, script,
	 * screenplay, or teleplay.
	 *
	 * @return string|boolean List of writers as a string
	 */
	public function get_writers() {
		return $this->to_summary_string( 'writers_summary', ', ', true, true );
	}

	/**
	 * A general term for someone who creates a written work, be it a novel, script,
	 * screenplay, or teleplay.
	 *
	 * @return string|boolean List of writers as a string without link to imdb
	 */
	public function get_writers_nolink() {
		return $this->to_summary_string( 'writers_summary', ', ', false );
	}

	/**
	 * Check if a string is empty or not
	 *
	 * @param string $value A string
	 *
	 * @return boolean true if is not empty false if is empty
	 */
	protected function is_not_empty( $value ) {
		return ! empty( $value );
	}
}
