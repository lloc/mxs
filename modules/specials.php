<?php

class BWP_GXS_MODULE_SPECIALS extends BWP_GXS_MODULE {

    public function __construct() {
        $this->set_current_time();
        $this->build_data();
    }

    public function build_data() {
        global $wpdb;
        $sql = "SELECT p.ID, p.post_modified, COUNT( p.ID ) FROM {$wpdb->posts} p, {$wpdb->postmeta} m WHERE p.ID = m.post_id AND m.meta_key IN( 'latitude', 'longitude' ) GROUP BY p.ID, p.post_modified HAVING COUNT( p.ID ) = 2";
        $data = array();
        foreach ( $wpdb->get_results( $sql ) as $post ) {
            $data             = $this->init_data( $data );
            $data['location'] = home_url( '/import/index.php?post_id=' . $post->ID );
            $data['lastmod']  = $this->format_lastmod( strtotime( $post->post_modified ) );
            $data['freq']     = $this->cal_frequency( null, $post->post_modified );
            $data['priority'] = '1.0';
            $this->data[]     = $data;
        }
        return true;
    }

}
