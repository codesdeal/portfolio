<?php
class _themename_Mega_Menu_Walker extends Walker_Nav_Menu {
    private $mega_menu_classes;

    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $submenu_class = 'dropdown-menu';
        
        // Add mega-menu class for top-level submenus if needed
        if ($depth === 0 && in_array('mega-menu', $this->mega_menu_classes)) {
            $submenu_class .= ' c-mega-menu';
        }

        // Position submenus properly for nested levels
        if ($depth >= 1) {
            $submenu_class .= ' dropdown-submenu';
        }
        
        $output .= "\n$indent<ul class=\"$submenu_class\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = str_repeat("\t", $depth);
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        // Store mega menu classes for later use
        $this->mega_menu_classes = $classes;
        
        if ($depth === 0) {
            $classes[] = 'nav-item';
            if ($args->walker->has_children) {
                $classes[] = 'dropdown';
            }
        } else {
            if ($args->walker->has_children) {
                $classes[] = 'dropdown-item-submenu';
            }
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= $indent . '<li' . $class_names . '>';
        
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        
        if ($depth === 0) {
            $atts['class'] = 'nav-link';
            if ($args->walker->has_children) {
                $atts['class'] .= ' dropdown-toggle';
            }
        } else {
            $atts['class'] = 'dropdown-item';
            if ($args->walker->has_children) {
                $atts['class'] .= ' dropdown-toggle';
            }
        }
        
        // Add active class if current item
        if (in_array('current-menu-item', $classes)) {
            $atts['class'] .= ' active';
            $atts['aria-current'] = 'page';
        }
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}