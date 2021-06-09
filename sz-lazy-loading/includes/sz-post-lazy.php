<?php
// Add Lazy loading to post content

function content_img_lazy($content) {
    // Create a new istance of DOMDocument
    $post = new DOMDocument();
    // Load $content as HTML
    libxml_use_internal_errors(true);
	$post->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
	libxml_use_internal_errors(false);
    // Look up for all the <img> tags.
    $imgs = $post->getElementsByTagName('img');

    // Iteration time
    foreach( $imgs as $img ) {
        // Let's make sure the img has not been already manipulated by us
        // by checking if it has a data-src attribute (we could also check
        // if it has the fs-img class, or whatever check you might feel is
        // the most appropriate.
        if( $img->hasAttribute('data-src') ) continue;


        // Also, let's check that the <img> we found is not child of a <noscript>
        // tag, we want to leave those alone as well.
        if( $img->parentNode->tagName == 'noscript' ) continue;

        // Let's clone the node for later usage.
        $clone = $img->cloneNode();

        // Get the src attribute, remove it from the element, swap it with
        // data-src
        $src = $img->getAttribute('src');
        $img->setAttribute('data-src', $src);
        $srct = plugins_url();
        if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
} else{
                        $img->setAttribute('src', $srct.'/sz-lazy-loading/img/sz-loader1.gif' );
}

        $width = $img->getAttribute('width');
        $img->removeAttribute('width');
        $img->setAttribute('data-width', $width);

        $height = $img->getAttribute('height');
        $img->removeAttribute('height');
        $img->setAttribute('data-height', $height);

		// Get the class and add lazy to the existing classes
        $imgClass = $img->getAttribute('class');
        $img->setAttribute('class', $imgClass . ' lazy');

        // Let's create the <noscript> element and append our original
        // tag, which we cloned earlier, as its child. Then, let's insert
        // it before our manipulated element
        $no_script = $post->createElement('noscript');
        $no_script->appendChild($clone);
        $img->parentNode->insertBefore($no_script, $img);
    };

     return $post->saveHTML();
 }

 add_filter('the_content', 'content_img_lazy');