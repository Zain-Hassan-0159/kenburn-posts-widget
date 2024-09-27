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

		$the_query = new WP_Query( 
			array( 
			  'posts_per_page' => $settings['no_of_posts'], 
			  'post_type' => $settings['post_type'],
			  'orderby' => $settings['order_by'], 
			  'order' => $settings['order'], 
			) 
		);

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
                                                style="opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);">Read full post</div>
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