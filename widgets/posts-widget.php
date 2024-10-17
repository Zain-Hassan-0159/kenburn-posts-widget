<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Posts.
 *
 *
 * @since 1.0.0
 */
class Elementor_cpw_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Product Posts widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cpw';
	}

	/**
	 * Get widget title.
	 *
	 * Product Posts widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Posts', 'hz-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Product Posts widget icon.
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
		return [ 'cpw-category' ];
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
		return [ 'posts', 'Product', 'custom' ];
	}

    public function get_style_depends() {
		return [ 'posts-widget' ];
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
				'default' => 4,
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
                'default' => esc_html__('Meer Opties', 'hz-widgets'),
                'label_block' => true,
            ]
        );

		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					// 'custom_attributes' => '',
				],
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
				'selector' => '{{WRAPPER}} .products .product .text h2',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
                'label' => esc_html__( 'Content Typography', 'hz-widgets' ),
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .products .product .text p',
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'cat_btn_typography',
                'label' => esc_html__( 'Cat Button Typography', 'hz-widgets' ),
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .products .product .image a',
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
                'label' => esc_html__( 'Link Typography', 'hz-widgets' ),
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .products .product .call_to_action a',
			]
		);
        
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .products .product .text h2' => 'color: {{VALUE}};',
				],
			]
		);
        
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Title Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .products .product .text p' => 'color: {{VALUE}};',
				],
			]
		);
        
		$this->add_control(
			'cat_btn_title_color',
			[
				'label' => esc_html__( 'Cat Title Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .products .product .image a' => 'color: {{VALUE}};',
				],
			]
		);
        
		$this->add_control(
			'cat_btnbg_color',
			[
				'label' => esc_html__( 'Cat Bg Color', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .products .product .image a' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .products .product .call_to_action a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .products .product .call_to_action a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_height',
			[
				'label' => esc_html__( 'Items Height', 'hz-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 355,
				],
				'selectors' => [
					'{{WRAPPER}} .products .product .image' => 'min-height: {{SIZE}}{{UNIT}};',
				],
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
		<div class="products">
		<?php
			if( $the_query->have_posts() ) :
				while( $the_query->have_posts() ): $the_query->the_post();
				$post_id = get_the_ID();
				$post_image = get_the_post_thumbnail_url();
				$post_title = get_the_title();
				$terms = get_the_terms( get_the_ID(), 'product-categories' );
				?>
				<div class="product">
					<div class="image">
					<img src="<?php echo $post_image; ?>" alt="<?php echo $post_title; ?>">
					<?php
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

						foreach ( $terms as $term ) {
							// Output the category name with link
							echo '<a class="cat" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
							break;
						}
					}
					?>
					</div>
					<div class="text">
						<h2><?php echo $post_title; ?></h2>
						<p><?php echo get_the_excerpt(); ?></p>
					</div>
					<div class="call_to_action">
					<div class="cart">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
					</div>
					<?php
					if ( ! empty( $settings['btn_link']['url'] ) ) {
						$link = $settings['btn_link']['url'] . '?prod_id=' . $post_id;
					}
					?>
					<a href="<?php echo $link; ?>" ><?php echo $settings['btn_title']; ?></a>
					</div>
				</div>
				<?php
				
			endwhile;
				wp_reset_postdata();
			endif;
		?>
		</div>
		<?php
	}

}