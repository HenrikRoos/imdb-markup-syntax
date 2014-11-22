<?php
/**
 * Handler for images in WordPress Media Library. Download and save images into
 * Media Library.
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

require_once 'runtime-exception.php';
require_once 'wp-exception.php';

/**
 * Handler for images in WordPress Media Library. Download and save images into
 * Media Library.
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Media_Library_Handler {

	/** @var int Current Post ID */
	public $post_id;
	/** @var string Valid URL to the image remote */
	public $remote_url;
	/** @var string Filename e.g tconst */
	public $fileanme;

	/**
	 * Create an intsans and validate input
	 *
	 * @param int $post_id Current Post ID
	 * @param string $remote_url Valid URL to the image remote
	 * @param string $filename Filename with no extension on new file e.g tconst
	 *
	 * @since WordPress 2.5
	 *
	 * @throws Runtime_Exception If no valid input.
	 */
	public function __construct( $post_id, $remote_url, $filename ) {
		if ( ! is_int( $post_id ) ) {
			throw new Runtime_Exception(
				__( 'Post ID must be an integer', 'imdb-markup-syntax' )
			);
		}
		if ( filter_var( $remote_url, FILTER_VALIDATE_URL ) === false ) {
			throw new Runtime_Exception(
				__( 'Remote URL must be an validate URL', 'imdb-markup-syntax' )
			);
		}
		if ( ! file_is_displayable_image( $remote_url ) ) {
			throw new Runtime_Exception(
				__( 'No valid displayable image', 'imdb-markup-syntax' )
			);
		}
		$info           = pathinfo( $remote_url );
		$this->fileanme = $filename . '.' . $info['extension'];

		$this->remote_url = $remote_url;
		$this->post_id    = $post_id;
	}

	/**
	 * Get html code for image and link to the movie at imdb.com
	 *
	 * @param string $href Link to the movie at imdb.com, null if no url
	 * @param string $title Name of the movie
	 * @param string $size Thumbnail sizes: thumbnail, medium, large, full
	 * @param string $align Alignment: none, left, center, right
	 *
	 * @since WordPress 3.1
	 *
	 * @throws WP_Exception      Error from retrieve the raw response
	 * @throws Runtime_Exception Error from wp_upload_bits or with metadata update
	 *
	 * @return string html code with a href tag to the movie if $href is present
	 */
	public function get_html( $href, $title, $size = 'medium', $align = 'none' ) {
		$file      = $this->download();
		$attach_id = $this->add_to_media_library(
			$file['file'], $title, $file['content-type']
		);
		$thumbnail = @set_post_thumbnail( $this->post_id, $attach_id );
		if ( $thumbnail === false ) {
			throw new Runtime_Exception(
				sprintf( __( 'Can\'t set thumbnail to the Post ID %d', 'imdb-markup-syntax' ), $this->post_id )
			);
		}
		$img = get_the_post_thumbnail(
			$this->post_id, $size,
			array( 'class' => 'align' . $align . ' size-' . $size )
		);
		if ( $href == null ) {
			return $img;
		}

		return '<a href="' . $href . '" title="' . $title . '">' . $img . '</a>';
	}

	/**
	 * Download the remot file and save it `in the upload folder.
	 * /---code php
	 * array(
	 *      "file" => string, //unique file path
	 *      "url" => string, //link to the new file
	 *      "content-type" => string, //e.g image/jpeg
	 *      "error" => false|string
	 * );
	 * \---
	 *
	 * @since WordPress 2.7
	 *
	 * @throws WP_Exception      Some error from retrieve the raw response
	 * @throws Runtime_Exception Some error from wp_upload_bits
	 *
	 * @return array File and url info in a array.
	 */
	protected function download() {
		//Retrieve the raw response from the HTTP request using the GET method.
		$get = wp_remote_get( $this->remote_url );
		if ( is_wp_error( $get ) ) {
			throw new WP_Exception( $get );
		}

		//Create a file in the upload folder with given content.
		$bits       = wp_remote_retrieve_body( $get );
		$local_file = wp_upload_bits( $this->fileanme, null, $bits );
		if ( $local_file['error'] ) {
			throw new Runtime_Exception( $local_file['error'] );
		}
		$local_file['content-type'] = $get['headers']['content-type'];

		return $local_file;
	}

	/**
	 * Inserts an attachment into the media library. Generates metadata for an image
	 * attachment. It also creates a thumbnail and other intermediate sizes of the
	 * image attachment based on the sizes defined on the
	 * "Settings_Media_Screen":http://codex.wordpress.org/Settings_Media_Screen
	 *
	 * @param string $filepath Filepath of the attached image in upload folder.
	 * @param string $title Name of the movie.
	 * @param string $mime_type File content-type *e.g image/jpeg*
	 *
	 * @since WordPress 2.1
	 *
	 * @return int Attachment ID
	 */
	protected function add_to_media_library( $filepath, $title, $mime_type ) {
		$wp_upload_dir = wp_upload_dir();
		$attachment    = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filepath ),
			'post_title'     => $title,
			'post_mime_type' => $mime_type,
			'post_content'   => '',
		);
		$attach_id     = @wp_insert_attachment( $attachment, $filepath, $this->post_id );
		$attach_data   = wp_generate_attachment_metadata( $attach_id, $filepath );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

}
