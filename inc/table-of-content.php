<?php
/**
 * Table of contents based on headings in the post content.
 *
 * This code snippet generates a table of contents (TOC) for a post based on the headings present in the content.
 *
 * @package _themename
 */

// Initialize a global variable to store the TOC
global $_themename_toc_storage;
$_themename_toc_storage = '';

/**
 * Generate Table of Contents and add IDs to headings via the_content filter.
 *
 * @param string $content The original post content.
 * @return string The modified post content with IDs added to headings.
 */
function _themename_generate_toc_via_content_filter($content) {
    global $_themename_toc_storage;

    // Initialize DOMDocument
    libxml_use_internal_errors(true); // Suppress parsing errors
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);

    // Get all h2 and h3 elements
    $headings = $dom->getElementsByTagName('*');
    $toc = '';

    foreach ($headings as $heading) {
        if (in_array($heading->nodeName, ['h2', 'h3'])) {
            $heading_text = trim($heading->textContent);
            if (!empty($heading_text)) {
                // Generate a unique anchor
                $anchor = sanitize_title($heading_text);

                // Ensure the anchor is unique by appending a number if necessary
                $original_anchor = $anchor;
                $i = 1;
                while ($dom->getElementById($anchor)) {
                    $anchor = $original_anchor . '-' . $i;
                    $i++;
                }

                // Add the id attribute to the heading
                $heading->setAttribute('id', $anchor);

                // Add indentation class for h3
                $indent_class = $heading->nodeName === 'h3' ? ' toc-indent' : '';
                
                // Append to TOC with indentation class
                $toc .= sprintf(
                    '<li class="custom-margin-bottom-li custom-li-color%s"><a href="#%s">%s</a></li>',
                    $indent_class,
                    esc_attr($anchor),
                    esc_html($heading_text)
                );
            }
        }
    }

    // Save the modified HTML
    $modified_content = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));

    // Extract the body content
    preg_match('/<body>(.*?)<\/body>/s', $modified_content, $body_matches);
    if (isset($body_matches[1])) {
        $_themename_toc_storage = $toc;
        return $body_matches[1];
    }

    // If parsing fails, return original content
    return $content;
}
add_filter('the_content', '_themename_generate_toc_via_content_filter', 20);

/**
 * Function to retrieve the generated Table of Contents.
 *
 * @return string HTML unordered list representing the TOC.
 */
function _themename_get_table_of_contents() {
    global $_themename_toc_storage;
    if (!empty($_themename_toc_storage)) {
        return '<ul class="custom-list-style-none">' . $_themename_toc_storage . '</ul>';
    }
    return '';
}