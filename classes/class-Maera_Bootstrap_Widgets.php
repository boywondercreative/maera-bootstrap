<?php

if ( ! class_exists( 'Maera_Bootstrap_Widgets' ) ) {

	/**
	* The Bootstrap Shell module
	*/
	class Maera_Bootstrap_Widgets {

		/**
		 * Class constructor
		 */
		public function __construct() {
			add_filter( 'maera/widgets/class', array( $this, 'widgets_class' ) );
			add_filter( 'maera/widgets/title/before', array( $this, 'widgets_before_title' ) );
			add_filter( 'maera/widgets/title/after', array( $this, 'widgets_after_title' ) );
			add_action( 'widgets_init', array( $this, 'maera_widget_areas' ) );
			add_action( 'init', array( $this, 'extra_widget_areas' ) );
		}


		/**
		 * Return an array of the extra widget area regions
		 */
		function extra_widget_areas_array( $areas = array() ) {

			$areas['body_top'] = array(
				'name'     => __( 'Body Top', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/header/before/ewa',
				'priority' => 20,
				'class'    => 'row',
			);
			$areas['pre_header'] = array(
				'name'     => __( 'Pre-Header', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/extra_header/before/ewa',
				'priority' => 20,
				'class'    => 'row',
			);
			$areas['header'] = array(
				'name'     => __( 'Header', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/extra_header/widgets/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['post_header'] = array(
				'name'     => __( 'Post-Header', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/extra_header/after/ewa',
				'priority' => 15,
				'class'    => 'row',
			);
			$areas['jumbotron'] = array(
				'name'     => __( 'Jumbotron', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/jumbotron/content/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['pre_content'] = array(
				'name'     => __( 'Pre-Content', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/wrap/before/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['pre_main'] = array(
				'name'     => __( 'Pre-Main', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/content/before/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['post_main'] = array(
				'name'     => __( 'Post-Main', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/content/after/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['pre_footer'] = array(
				'name'     => __( 'Pre-Footer', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/footer/before/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['footer'] = array(
				'name'     => __( 'Footer', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/footer/content/ewa',
				'priority' => 10,
				'class'    => 'row',
			);
			$areas['post_footer'] = array(
				'name'     => __( 'Post-Footer', 'maera_bootstrap' ),
				'default'  => 0,
				'action'   => 'maera/footer/after/ewa',
				'priority' => 10,
				'class'    => 'row',
			);

			return $areas;

		}


		/**
		 * Get the widget class
		 */
		function widgets_class() {

			$widgets_mode = get_theme_mod( 'widgets_mode', 'none' );

			if ( 'panel' == $widgets_mode ) {
				return 'panel panel-default';
			} elseif ( 'well' == $widgets_mode ) {
				return 'well';
			}

		}


		/**
		 * Widgets 'before_title' modifying based on widgets mode.
		 */
		function widgets_before_title() {

			$widgets_mode = get_theme_mod( 'widgets_mode', 'none' );

			if ( 'panel' == $widgets_mode ) {
				return '<div class="panel-heading">';
			} elseif ( 'well' == $widgets_mode ) {
				return '<h3 class="widget-title">';
			}

		}


		/**
		 * Widgets 'after_title' modifying based on widgets mode.
		 */
		function widgets_after_title() {

			$widgets_mode = get_theme_mod( 'widgets_mode', 'none' );

			if ( 'panel' == $widgets_mode ) {
				return '</div><div class="panel-body">';
			} elseif ( 'well' == $widgets_mode ) {
				return '</h3>';
			}

		}

		/**
		 * Register secondary sidebar
		 */
		function maera_widget_areas() {
			global $maera_i18n;
			$class        = apply_filters( 'maera/widgets/class', '' );
			$before_title = apply_filters( 'maera/widgets/title/before', '<h3 class="widget-title">' );
			$after_title  = apply_filters( 'maera/widgets/title/after', '</h3>' );
			// Sidebars
			register_sidebar( array(
				'name'          => $maera_i18n['secondarysidebar'],
				'id'            => 'sidebar_secondary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			$extra_widget_areas = $this->extra_widget_areas_array();

			foreach ( $extra_widget_areas as $extra_widget_area => $options ) {

				if ( 0 != get_theme_mod( $extra_widget_area . '_toggle', 0 ) ) {

					register_sidebar( array(
						'name'          => $options['name'],
						'id'            => $extra_widget_area,
						'before_widget' => '<section id="%1$s" class="row ' . $class . ' widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => $before_title,
						'after_title'   => $after_title,
					) );

				}

			}

		}

		function extra_widget_areas() {
			$extra_widget_areas = $this->extra_widget_areas_array();

			$widget_width = new Maera_Widget_Dropdown(
			array(
				'id'      => 'maera_widget_width',
				'label'   => __( 'Width' ),
				'choices' => array(
					1   => array( 'label' => 1,  'classes' => 'col-md-1' ),
					2   => array( 'label' => 2,  'classes' => 'col-md-2' ),
					3   => array( 'label' => 3,  'classes' => 'col-md-3' ),
					4   => array( 'label' => 4,  'classes' => 'col-md-4' ),
					5   => array( 'label' => 5,  'classes' => 'col-md-5' ),
					6   => array( 'label' => 6,  'classes' => 'col-md-6' ),
					7   => array( 'label' => 7,  'classes' => 'col-md-7' ),
					8   => array( 'label' => 8,  'classes' => 'col-md-8' ),
					9   => array( 'label' => 9,  'classes' => 'col-md-9' ),
					10  => array( 'label' => 10, 'classes' => 'col-md-10' ),
					11  => array( 'label' => 11, 'classes' => 'col-md-11' ),
					12  => array( 'label' => 12, 'classes' => 'col-md-12' ),
				),
				'default' => 12,
				)
			);

		}

	}

}
