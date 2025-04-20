<?php
class _Themename_Sitemap_Generator {
    private $sitemap_urls = [];

    public function __construct() {
        add_action('init', [$this, 'schedule_sitemap_generation']);
        add_action('_themename_generate_sitemap', [$this, 'generate_sitemap']);
        add_action('save_post', [$this, 'regenerate_sitemap']);
    }

    public function schedule_sitemap_generation() {
        if (!wp_next_scheduled('_themename_generate_sitemap')) {
            wp_schedule_event(time(), 'daily', '_themename_generate_sitemap');
        }
    }

    public function regenerate_sitemap() {
        wp_clear_scheduled_hook('_themename_generate_sitemap');
        $this->generate_sitemap();
    }

    public function generate_sitemap() {
        $this->get_post_urls();
        $this->get_taxonomy_urls();
        $this->get_author_urls();

        $sitemap_content = $this->build_sitemap_content();
        $sitemap_path = ABSPATH . 'sitemap.xml';
        file_put_contents($sitemap_path, $sitemap_content);

        // Ping search engines
        $this->ping_search_engines();
    }

    private function get_post_urls() {
        $posts = get_posts([
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        foreach ($posts as $post) {
            $this->sitemap_urls[] = [
                'loc' => get_permalink($post),
                'lastmod' => get_the_modified_date('c', $post),
                'priority' => $this->get_post_priority($post),
                'changefreq' => $this->get_post_changefreq($post),
            ];
        }
    }

    private function get_taxonomy_urls() {
        $taxonomies = get_taxonomies(['public' => true], 'names');
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => true]);
            foreach ($terms as $term) {
                $this->sitemap_urls[] = [
                    'loc' => get_term_link($term),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            }
        }
    }

    private function get_author_urls() {
        $authors = get_users(['has_published_posts' => true]);
        foreach ($authors as $author) {
            $this->sitemap_urls[] = [
                'loc' => get_author_posts_url($author->ID),
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ];
        }
    }

    private function get_post_priority($post) {
        // Assign priority based on post type and date
        switch ($post->post_type) {
            case 'page':
                return '0.8';
            case 'post':
                // Higher priority for newer posts
                $age_in_days = (time() - strtotime($post->post_date)) / DAY_IN_SECONDS;
                return $age_in_days < 30 ? '0.7' : '0.5';
            default:
                return '0.4';
        }
    }

    private function get_post_changefreq($post) {
        $age_in_days = (time() - strtotime($post->post_date)) / DAY_IN_SECONDS;
        
        if ($age_in_days < 7) {
            return 'daily';
        } elseif ($age_in_days < 30) {
            return 'weekly';
        } elseif ($age_in_days < 180) {
            return 'monthly';
        } else {
            return 'yearly';
        }
    }

    private function build_sitemap_content() {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<?xml-stylesheet type="text/xsl" href="' . get_template_directory_uri() . '/assets/sitemap.xsl"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->sitemap_urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . esc_url($url['loc']) . '</loc>';
            if (isset($url['lastmod'])) {
                $xml .= '<lastmod>' . esc_html($url['lastmod']) . '</lastmod>';
            }
            if (isset($url['changefreq'])) {
                $xml .= '<changefreq>' . esc_html($url['changefreq']) . '</changefreq>';
            }
            if (isset($url['priority'])) {
                $xml .= '<priority>' . esc_html($url['priority']) . '</priority>';
            }
            $xml .= '</url>';
        }

        $xml .= '</urlset>';
        return $xml;
    }

    private function ping_search_engines() {
        $sitemap_url = home_url('sitemap.xml');
        $engines = [
            'https://www.google.com/webmasters/tools/ping?sitemap=' . urlencode($sitemap_url),
            'https://www.bing.com/ping?sitemap=' . urlencode($sitemap_url)
        ];

        foreach ($engines as $engine) {
            wp_remote_get($engine);
        }
    }
}

// Initialize sitemap generator
new _Themename_Sitemap_Generator();