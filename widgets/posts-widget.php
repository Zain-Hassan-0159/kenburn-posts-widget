<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * KenBurn Posts.
 *
 *
 * @since 1.0.0
 */
class Elementor_kpbw_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Kenburn Posts widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'kbpw';
	}

	/**
	 * Get widget title.
	 *
	 * Kenburn Posts widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'KenBurn Posts', 'hz-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Kenburn Posts widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-justified';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'kpbw-category' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'posts', 'kenburn', 'custom' ];
	}

    public function get_style_depends() {
		return [ 'kenburn-posts-widget' ];
	}

    public function get_script_depends() {
		return [ 'kenburn-posts-widget' ];
	}

	private function get_posts_titles_ids() {
		// Retrieve all registered post types
		$post_types = get_post_types(array('public' => true), 'names', 'and');
	
		// Initialize an empty array to store post titles and IDs
		$posts_array = array();
	
		// Loop through each post type
		foreach ($post_types as $post_type) {
			// Retrieve all posts of the current post type
			$posts = get_posts(array(
				'posts_per_page' => -1,
				'post_type'      => $post_type,
				'post_status'    => 'publish',
			));
	
			// Loop through each post and add its title and ID to the array
			foreach ($posts as $post) {
				$posts_array[$post->ID] = esc_html__($post->post_title, 'hz-widgets');
			}
		}
	
		// Return the array
		return $posts_array;
	}
	

	/**
	 * Get all terms with their term IDs (keys) and prefixed names (taxonomy: term name).
	 *
	 * @return array Array of terms with term ID as key and 'taxonomy: term name' as value.
	 */
	function get_all_terms_with_taxonomy_prefix() {
		// Get all registered taxonomies
		$taxonomies = get_taxonomies( array( 'public' => true ), 'names' ); // Get public taxonomies
		$terms_array = array();

		// Get terms from the specified taxonomies
		$terms = get_terms( array(
			'taxonomy'   => $taxonomies,
			'hide_empty' => false, // Show terms even if they are not associated with any posts
		) );

		// Check if terms were retrieved
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			// Loop through each term and store its ID and prefixed name
			foreach ( $terms as $term ) {
				// Get the taxonomy name (e.g., 'category', 'post_tag', or custom taxonomy)
				$taxonomy_prefix = $term->taxonomy;
				
				// Store the term ID as key and 'taxonomy: term name' as value
				$terms_array[ $taxonomy_prefix . ':' . $term->term_id ] = $taxonomy_prefix . ': ' . $term->name;
			}
		}

		return $terms_array;
	}




	/**
	 * Register list widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'General', 'hz-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Post Type', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_all_custom_post_type()
			]
		);

		$this->add_control(
			'no_of_posts',
			[
				'label' => esc_html__( 'No of Posts', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'step' => 1,
				'default' => 6,
			]
		);

		$this->start_controls_tabs(
			'query_tabs'
		);
		
		$this->start_controls_tab(
			'include',
			[
				'label' => esc_html__( 'Include', 'hz-widgets' ),
			]
		);

		$this->add_control(
			'include_posts_by',
			[
				'label' => esc_html__( 'Include By', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => false,
				'options' => [
					'title' => 'Post Title',
					'terms' => 'Terms',
				],
				'default' => [],
			]
		);

		$this->add_control(
			'include_posts_titles',
			[
				'label' => esc_html__( 'Post Titles', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_posts_titles_ids(),
				'default' => [],
				'condition' => [ 'include_posts_by' => 'title' ]
			]
		);

		$this->add_control(
			'include_posts_terms',
			[
				'label' => esc_html__( 'Terms', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_all_terms_with_taxonomy_prefix(),
				'default' => [],
				'condition' => [ 'include_posts_by' => 'terms' ]
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'exclude',
			[
				'label' => esc_html__( 'Exclude', 'hz-widgets' ),
			]
		);

		$this->add_control(
			'exclude_posts_by',
			[
				'label' => esc_html__( 'Exclude By', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => false,
				'options' => [
					'title' => 'Post Title',
					'terms' => 'Terms',
				],
				'default' => [],
			]
		);

		$this->add_control(
			'exclude_posts_titles',
			[
				'label' => esc_html__( 'Post Titles', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_posts_titles_ids(),
				'default' => [],
				'condition' => [ 'exclude_posts_by' => 'title' ]
			]
		);

		$this->add_control(
			'exclude_posts_terms',
			[
				'label' => esc_html__( 'Terms', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_all_terms_with_taxonomy_prefix(),
				'default' => [],
				'condition' => [ 'exclude_posts_by' => 'terms' ]
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_control(
			'order_by',
			[
				'label' => esc_html__( 'Order By', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'title' => 'Title',
					'rand' => 'Random',
					'date' => 'Date',
				]
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'ASC' => 'Ascending',
					'DESC' => 'Descending',
				]
			]
		);

		$this->add_control(
            'btn_title',
            [
                'label' => esc_html__('Button Title', 'hz-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Read full post', 'hz-widgets'),
                'label_block' => true,
            ]
        );

	
		$this->end_controls_section();

        $this->start_controls_section(
			'style',
			[
				'label' => esc_html__( 'Content', 'hz-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
                'label' => esc_html__( 'Title Typography', 'hz-widgets' ),
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .link-panel.tv .details h4',
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
                'label' => esc_html__( 'Button Typography', 'hz-widgets' ),
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .link-panel .btn',
			]
		);
        
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .link-panel.tv .details h4' => 'color: {{VALUE}};',
				],
			]
		);
        
		$this->add_control(
			'btn_title_color',
			[
				'label' => esc_html__( 'Button Title Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .link-panel .btn' => 'color: {{VALUE}};',
				],
			]
		);
        
		$this->add_control(
			'btnbg_color',
			[
				'label' => esc_html__( 'Button Bg Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .link-panel .btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border_content',
				'selector' => '{{WRAPPER}} .border',
			]
		);

		$this->end_controls_section();



	}

    
	public function get_all_custom_post_type(){
		// this is all custom post types
		$args       = array(
			'public' => true,
		);
		$post_types = get_post_types( $args, 'objects' );
		$posts = array();
		foreach ($post_types as $post_type) {
			$posts[$post_type->name] = $post_type->labels->singular_name;
		}

		return $posts;
		// this is all custom post types
	}


	/**
	 * Render list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Start building the WP_Query arguments
		$args = array(
			'posts_per_page' => $settings['no_of_posts'], 
			'post_type'      => $settings['post_type'],
			'orderby'        => $settings['order_by'], 
			'order'          => $settings['order'], 
		);

		// Include by post title
		if ( 'title' === $settings['include_posts_by'] && ! empty( $settings['include_posts_titles'] ) ) {
			$args['post__in'] = $settings['include_posts_titles'];  // Include posts by IDs from selected titles
		}

		// Exclude by post title
		if ( 'title' === $settings['exclude_posts_by'] && ! empty( $settings['exclude_posts_titles'] ) ) {
			$args['post__not_in'] = $settings['exclude_posts_titles'];  // Exclude posts by IDs from selected titles
		}

		// Include by terms
		if ( 'terms' === $settings['include_posts_by'] && ! empty( $settings['include_posts_terms'] ) ) {
			$tax_queries = [];

			foreach ( $settings['include_posts_terms'] as $term ) {
				list( $taxonomy, $term_id ) = explode( ':', $term );

				// Add each term to the tax query
				$tax_queries[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => (int) $term_id,
				);
			}

			// Apply the 'AND' relation to ensure all terms are matched
			$args['tax_query'][] = array(
				'relation' => 'AND',  // Ensure posts must match all terms across taxonomies
				...$tax_queries       // Spread operator to add all queries
			);
		}

		// Exclude by terms
		if ( 'terms' === $settings['exclude_posts_by'] && ! empty( $settings['exclude_posts_terms'] ) ) {
			$exclude_tax_queries = [];

			foreach ( $settings['exclude_posts_terms'] as $term ) {
				list( $taxonomy, $term_id ) = explode( ':', $term );

				// Add each exclusion term to the tax query
				$exclude_tax_queries[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => (int) $term_id,
					'operator' => 'NOT IN',  // Exclude these terms
				);
			}

			// Apply 'AND' relation to ensure all exclusion terms are applied
			$args['tax_query'][] = array(
				'relation' => 'AND',  // Ensure all exclude terms are processed
				...$exclude_tax_queries // Spread operator to add all exclusion queries
			);
		}

		// Create the query
		$the_query = new WP_Query( $args );


        ?>
        <section class="panel-w tv-wall">
            <div id="tv-grid" class="row apop border" style="opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);">
                <div class="items">
                    <?php
                        if( $the_query->have_posts() ) :
                            while( $the_query->have_posts() ): $the_query->the_post();
                            $post_id = get_the_ID();
                            $post_image = get_the_post_thumbnail_url();
                            $post_title = get_the_title();
                            ?>
                            <div class="col-sm-4 item border">
                                <a href="<?php echo get_the_permalink(); ?>"
                                    data-background="home<?php echo $post_id; ?>" class="link-panel tv show">
                                    <div class="overlay-wrapper"
                                        style="background-image: url('<?php echo $post_image; ?>');"
                                        data-ll-status="loaded">
                                        <div class="details">
                                            <h4 data-fontsize="18" style="--fontSize: 18; line-height: 1.1; --minFontSize: 18;"
                                                data-lineheight="19.8px" class="fusion-responsive-typography-calculated"><?php echo $post_title; ?></h4>
                                        </div>
                                        <div class="overlay-grad"></div>
                                        <div class="overlay">
                                            <div class="btn btn-white btn-md"
                                                style="opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);"><?php echo $settings['btn_title']; ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                            
                        endwhile;
                            wp_reset_postdata();
                        endif;
                    ?>
                </div>

                <div class="bgs">
                    <?php
                        if( $the_query->have_posts() ) :
                            while( $the_query->have_posts() ): $the_query->the_post();
                            $post_id = get_the_ID();
                            $post_image = get_the_post_thumbnail_url();
                            ?>
                            <div id="home<?php echo $post_id; ?>" class="bg"
                                style="background-image: url('<?php echo $post_image; ?>');" >
                            </div>
                            <?php
                            
                        endwhile;
                            wp_reset_postdata();
                        endif;
                    ?>
                </div>
            </div>
        </section>
		<?php
	}

}