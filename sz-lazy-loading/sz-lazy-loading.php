<?php
/**
 * Classic Editor
 *
 * Plugin Name: SZ Lazy Loading
 * Description: Enables the WordPress SZ Lazy Loading Plugin to support lazy loading of all images etc. 
 * Version:     0.1.1
 * Author:      Sekar, Vetrivel
 * Author URI:  
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: sz-lazy-loading
 *
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__)); 
require_once MY_PLUGIN_PATH . 'includes/sz-post-lazy.php';

add_action('wp_head', 'wp_lazy_inline_style', 100);
function wp_lazy_inline_style()
{
 echo "<style>
	img:not([src]) {
		visibility: hidden;
    }

    /* Fixes Firefox anomaly during image load */
    @-moz-document url-prefix() {
        img:-moz-loading {
          visibility: hidden;
        }
      }
	  </style>";
	  
}
add_action('wp_footer', 'wp_lazy_inline_script', 100);
function wp_lazy_inline_script()
{
?>
<script src="<?php echo plugins_url(); ?>/sz-lazy-loading/js/lazyload.min.js"></script>
<script type="text/javascript">
 (function () {
        function logElementEvent(eventName, element) {
          console.log(Date.now(), eventName, element.getAttribute("data-src"));
        }

        var callback_enter = function (element) {
          logElementEvent("🔑 ENTERED", element);
        };
        var callback_exit = function (element) {
          logElementEvent("🚪 EXITED", element);
        };
        var callback_loading = function (element) {
          logElementEvent("⌚ LOADING", element);
        };
        var callback_loaded = function (element) {
          logElementEvent("👍 LOADED", element);
        };
        var callback_error = function (element) {
          logElementEvent("💀 ERROR", element);
        };
        var callback_finish = function () {
          logElementEvent("✔️ FINISHED", document.documentElement);
        };
        var callback_cancel = function (element) {
          logElementEvent("🔥 CANCEL", element);
        };

        var ll = new LazyLoad({
          // Assign the callbacks defined above
          callback_enter: callback_enter,
          callback_exit: callback_exit,
          callback_cancel: callback_cancel,
          callback_loading: callback_loading,
          callback_loaded: callback_loaded,
          callback_error: callback_error,
          callback_finish: callback_finish
        });
      })();
</script>
<?php
}
/*
function sz_enqueue_script() {
wp_enqueue_script( 'lazyload', 'plugin_dir_path()' . '/js/lazyload.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'sz_enqueue_script' );
*/