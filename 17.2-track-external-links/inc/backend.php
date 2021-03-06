<?php

namespace f\tel;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


/**
 * Class Backend
 *
 * @since 0.1.0
 *
 * @package f\tel
 */
class Backend {

	/**
	 * @var \f\tel\Link_Table
	 * @since 0.1.0
	 */
	private $table;

	/**
	 * Backend constructor.
	 *
	 * @since 0.1.0
	 */
	function __construct() {

		add_action( 'admin_menu', [ $this, 'admin_menu' ], 9 );
	}


	/**
	 * Creates admin menu items.
	 *
	 * @since 0.1.0
	 */
	public function admin_menu() {

		$hook = add_menu_page(
			_x( 'Outgoing Links', 'Main page title', 'track-external-links' ),
			_x( 'Outgoing Links', 'Main menu title', 'track-external-links' ),
			'edit_posts',
			'tel',
			[ $this, 'links_page' ],
			'dashicons-admin-links'
		);

		add_action( 'load-' . $hook, [ $this, 'before_link_table_view' ] );
	}


	/**
	 * Tasks to do before table gets rendered.
	 *
	 * @since 0.1.0
	 */
	public function before_link_table_view() {

		require __DIR__ . '/link-table.php';

		$this->table = new Link_Table();
		$this->table->prepare_items();

		add_screen_option( 'per_page', array(
			'label'   => __( 'Links per page', 'track-external-links' ),
			'default' => 100,
			'option'  => 'links_per_page',
		) );
	}

	/**
	 * Prints the table view page.
	 *
	 * @since 0.1.0
	 */
	public function links_page() {

		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h1>

			<a class="page-title-action" href="#" onclick="return toggle_form()"><?php _e( 'Add new' ); ?></a>

			<hr class="wp-header-end"/>

			<?php settings_errors( 'tel' ); ?>

			<script>
				function toggle_form() {
					var form = document.getElementById( 'add_new_form' );
					if ( form.classList.contains( 'hidden' ) ) {
						form.classList.remove( 'hidden' );

						var form_input_elements = form.getElementsByTagName( 'input' );

						form_input_elements[ 0 ].value = '';
						form_input_elements[ 1 ].value = '';
						form_input_elements[ 2 ].value = '';
						form_input_elements[ 3 ].value = 'Save';
					} else {
						form.classList.add( 'hidden' );
					}

					return false;
				}

				function edit_link( triggered_el ) {
					var id    = parseInt( triggered_el.getAttribute( 'data-id' ) );
					var $tr   = triggered_el.parentNode.parentNode.parentNode.parentNode;
					var title = $tr.getElementsByTagName( 'a' )[ 0 ].innerHTML;
					var link  = $tr.getElementsByTagName( 'a' )[ 0 ].getAttribute( 'href' );

					var form = document.getElementById( 'add_new_form' );
					if ( form.classList.contains( 'hidden' ) ) {
						form.classList.remove( 'hidden' );
					}

					var form_input_elements = form.getElementsByTagName( 'input' );

					form_input_elements[ 0 ].value = id;
					form_input_elements[ 1 ].value = title;
					form_input_elements[ 2 ].value = link;
					form_input_elements[ 3 ].value = 'Update';

				}
			</script>

			<form id="add_new_form" class="hidden"
			      action="<?php echo esc_url( admin_url( 'admin.php?page=tel&action=new' ) ); ?>"
			      style="margin: 2em 0 0 0; border: 1px dotted #ccc; padding: 1em;" method="post">
				<input type="hidden" name="id" value=""/>
				<input class="regular-text code" type="text" required
				       placeholder="<?php _e( 'Enter Title...', 'track-external-links' ); ?>" name="title"/>
				<input class="regular-text code" type="url" required
				       placeholder="<?php _e( 'Enter Link...', 'track-external-links' ); ?>" name="link"/>
				<?php submit_button( __( 'Save', 'track-external-links' ), 'secondary', 'submit', false ); ?>
				<input type="hidden" name="_wpnonce"
				       value="<?php echo esc_attr( wp_create_nonce( 'f/tel/link/update' ) ); ?>"/>
			</form>

			<form action="<?php echo esc_url( admin_url( 'admin.php?page=tel' ) ); ?>" method="POST">
				<?php $this->table->display(); ?>
			</form>
		</div>

		<?php
	}
}


