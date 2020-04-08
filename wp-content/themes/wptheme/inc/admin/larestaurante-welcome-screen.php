<?php
/**
 * Welcome Screen Class
 * Sets up the welcome screen page, hides the menu item
 * and contains the screen content.
 */
class LaRestaurante_Welcome_Screen {

	/**
	 * Constructor  
	 * Sets up the welcome screen
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'larestaurante_welcome_register_menu' ) );
		add_action( 'load-themes.php', array( $this, 'larestaurante_activation_admin_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'larestaurante_welcome_style' ) );
		
		add_action( 'larestaurante_welcome', array( $this, 'larestaurante_welcome_intro' ), 			10 );
		add_action( 'larestaurante_welcome', array( $this, 'larestaurante_welcome_tabs' ), 				20 );
		add_action( 'larestaurante_welcome', array( $this, 'larestaurante_welcome_getting_started' ), 	30 );
		add_action( 'larestaurante_welcome', array( $this, 'larestaurante_welcome_pro' ), 				40 );
		add_action( 'larestaurante_welcome', array( $this, 'larestaurante_welcome_doc' ), 				60 );
		add_action( 'larestaurante_welcome', array( $this, 'larestaurante_welcome_about' ), 			70 );

	} // end constructor

	/**
	 * Adds an admin notice upon successful activation.
	 * @since 0.1
	 */
	public function larestaurante_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) { // input var okay
			add_action( 'admin_notices', array( $this, 'larestaurante_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 * @since 0.1
	 */
	public function larestaurante_welcome_admin_notice() {
		?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( esc_html__( 'Thanks for choosing LaRestaurante! Learn how to get the most out of your new theme on the %swelcome screen%s.', 'larestaurante' ), '<a href="' . esc_url( admin_url( 'themes.php?page=larestaurante-welcome' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=larestaurante-welcome' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with LaRestaurante', 'larestaurante' ); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Load welcome screen css
	 * @return void
	 * @since  0.1
	 */
	public function larestaurante_welcome_style() {
		
		wp_enqueue_style( 'larestaurante-welcome-screen', get_template_directory_uri() . '/inc/admin/css/welcome.css' );
	}

	/**
	 * Creates the dashboard page
	 * @see  add_theme_page()
	 * @since 1.0.0
	 */
	public function larestaurante_welcome_register_menu() {
		add_theme_page( 
		'LaRestaurante Theme Welcome Page',
		'LaRestaurante Theme',
		'read',
		'larestaurante-welcome',
		array( $this, 'larestaurante_welcome_screen' )
		);
	}

	/**
	 * The welcome screen
	 * @since 1.0.0
	 */
	public function larestaurante_welcome_screen() {
		?>
		<div class="wrap about-wrap">

			<?php  do_action( 'larestaurante_welcome' ); ?>

		</div>
		<?php
	}

	
	public function larestaurante_welcome_intro() {
		get_template_part( 'inc/admin/sections/intro' );
	}


	public function larestaurante_welcome_tabs() {
		get_template_part( 'inc/admin/sections/tabs' );
	}

	
	public function larestaurante_welcome_about() {
		get_template_part( 'inc/admin/sections/about-me' );
	}

	
	public function larestaurante_welcome_getting_started() {
		get_template_part( 'inc/admin/sections/lets-started' );
	}

	
	public function larestaurante_welcome_pro() {
		get_template_part( 'inc/admin/sections/plugin-support' );
	}
	
	public function larestaurante_welcome_doc() {
		get_template_part( 'inc/admin/sections/doc' );
	}
}

$GLOBALS['LaRestaurante_Welcome_Screen'] = new LaRestaurante_Welcome_Screen();