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
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax;

use IMDb_Markup_Syntax\Exceptions\Runtime_Exception;
use IMDb_Markup_Syntax\Exceptions\WP_Exception;

require_once dirname(__FILE__) . "/../../../wp-admin/includes/image.php";
require_once dirname(__FILE__) . "/Exceptions/WP_Exception.php";

/**
 * Handler for images in WordPress Media Library. Download and save images into
 * Media Library.
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Media_Library_Handler
{

    /** @var int Current Post ID */
    public $post_id;

    /** @var url Valid URL to the image remote */
    public $remote_url;

    /** @var string Filename e.g tconst */
    public $fileanme;

    /**
     * Create an intsans and validate input
     * 
     * @param int    $post_id    Current Post ID
     * @param url    $remote_url Valid URL to the image remote
     * @param string $filename   Filename with no extension on new file e.g tconst
     * 
     * @since 2.5
     * 
     * @throws Runtime_Exception If no valid input.
     */
    public function __construct($post_id, $remote_url, $filename)
    {
        if (!is_int($post_id)) {
            throw new Runtime_Exception(null, "post_id must be an integer");
        }
        if (filter_var($remote_url, FILTER_VALIDATE_URL) === false) {
            throw new Runtime_Exception(null, "remote_url must be an URL");
        }
        if (!file_is_displayable_image($remote_url)) {
            throw new Runtime_Exception(null, "No valid displayable image");
        }
        $info = pathinfo($remote_url);
        $this->fileanme = $filename . "." . $info["extension"];

        $this->remote_url = $remote_url;
        $this->post_id = $post_id;
    }

    /**
     * Get html code for image and link to the movie at imdb.com
     * 
     * @param string $href  Link to the movie at imdb.com
     * @param string $title Name of the movie
     * @param string $size  Thumbnail sizes: thumbnail, medium, large, full
     * @param string $align Alignment: left, center, right, none
     * 
     * @since 3.1 
     * 
     * @throws WP_Exception      Error from retrieve the raw response
     * @throws Runtime_Exception Error from wp_upload_bits or with metadata update
     * 
     * @return string html code
     */
    public function getHtml($href, $title, $size = "medium", $align = "left")
    {
        $file = $this->download();
        $attach_id = $this->addToMediaLibrary(
            $file["file"], $title, $file["content-type"]
        );
        set_post_thumbnail($this->post_id, $attach_id);
        $img = get_the_post_thumbnail(
            $this->post_id, $size,
            array("class" => "align" . $align . " size-" . $size)
        );
        return "<a href=\"{$href}\" title=\"{$title}\">{$img}</a>";
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
     * @since 2.7
     * 
     * @throws WP_Exception      Some error from retrieve the raw response
     * @throws Runtime_Exception Some error from wp_upload_bits
     * 
     * @return array File and url info in a array.
     */
    protected function download()
    {
        //Retrieve the raw response from the HTTP request using the GET method.
        $get = wp_remote_get($this->remote_url);
        if (is_wp_error($get)) {
            throw new WP_Exception($get);
        }

        //Create a file in the upload folder with given content.
        $bits = wp_remote_retrieve_body($get);
        $local_file = wp_upload_bits($this->fileanme, null, $bits);
        if ($local_file["error"]) {
            throw new Runtime_Exception(null, $local_file["error"]);
        }
        $local_file["content-type"] = $get["headers"]["content-type"];
        return $local_file;
    }

    /**
     * Inserts an attachment into the media library. Generates metadata for an image
     * attachment. It also creates a thumbnail and other intermediate sizes of the
     * image attachment based on the sizes defined on the
     * "Settings_Media_Screen":http://codex.wordpress.org/Settings_Media_Screen
     * 
     * @param string $filepath  Filepath of the attached image in upload folder.
     * @param string $title     Name of the movie.
     * @param string $mime_type File content-type *e.g image/jpeg*
     * 
     * @since 2.1
     * 
     * @throws Runtime_Exception Some issues with metadata update
     * 
     * @return int Attachment ID
     */
    protected function addToMediaLibrary($filepath, $title, $mime_type)
    {
        $attachment = array(
            "post_title" => $title,
            "post_mime_type" => $mime_type
        );
        $attach_id = @wp_insert_attachment($attachment, $filepath, $this->post_id);
        $attach_data = wp_generate_attachment_metadata($attach_id, $filepath);
        $update = wp_update_attachment_metadata($attach_id, $attach_data);
        if ($update === false) {
            throw new Runtime_Exception(null, "Can't update attachment metadata");
        }
        return $attach_id;
    }

}

?>
