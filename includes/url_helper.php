<?php
/**
 * URL Helper for SB Smart India
 * Generates SEO-friendly slugs and clean product URLs.
 */

if (!function_exists('createSlug')) {
    /**
     * Create a clean URL slug from a string (e.g. product title).
     *
     * @param string $string
     * @return string
     */
    function createSlug($string) {
        if (empty($string)) {
            return 'product';
        }
        
        // Convert to lowercase
        $slug = mb_strtolower(trim($string), 'UTF-8');
        
        // Replace & with 'and'
        $slug = str_replace('&', 'and', $slug);
        
        // Remove special characters except alphanumeric, spaces, and hyphens
        $slug = preg_replace('/[^\w\s-]/u', '', $slug);
        
        // Replace multiple spaces or hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        
        // Trim hyphens from beginning and end
        $slug = trim($slug, '-');
        
        return !empty($slug) ? $slug : 'product';
    }
}

if (!function_exists('getProductUrl')) {
    /**
     * Generate standard clean product URL.
     *
     * @param array|object|int $product
     * @param string $url_prefix E.g. '' or '../'
     * @return string
     */
    function getProductUrl($product, $url_prefix = '') {
        if (is_array($product)) {
            if (!empty($product['slug'])) {
                return $url_prefix . 'products/' . $product['slug'];
            }
            if (!empty($product['title'])) {
                return $url_prefix . 'products/' . createSlug($product['title']);
            }
            if (!empty($product['id'])) {
                return $url_prefix . 'product-details.php?id=' . (int)$product['id'];
            }
        } elseif (is_object($product)) {
            if (!empty($product->slug)) {
                return $url_prefix . 'products/' . $product->slug;
            }
            if (!empty($product->title)) {
                return $url_prefix . 'products/' . createSlug($product->title);
            }
            if (!empty($product->id)) {
                return $url_prefix . 'product-details.php?id=' . (int)$product->id;
            }
        } elseif (is_numeric($product) && $product > 0) {
            return $url_prefix . 'product-details.php?id=' . (int)$product;
        }
        
        return $url_prefix . 'products.php';
    }
}
?>
