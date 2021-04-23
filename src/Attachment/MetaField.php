<?php


namespace Palasthotel\WordPress\Attachment;


/**
 */
abstract class MetaField {

	protected $id;
	protected $label;
	protected $help;
	private $onSave;
	private $fnValue;

	public function __construct( string $id, int $priority) {
		$this->id    = $id;
		$this->label = "";
		$this->help  = "";

		$this->value(function($attachment_id){
			return get_post_meta( $attachment_id, $this->id, true );
		});

		$this->onSave(function ( $attachment_id, $value ) {
			update_post_meta( $attachment_id, $this->id, sanitize_text_field($value));
		});

		add_filter( 'attachment_fields_to_edit', function ( $form_fields, $post ) {
			$form_fields[ $this->id ] = $this->field( [], $post );

			return $form_fields;
		}, $priority, 2 );
		add_action( 'edit_attachment', function ( $attachment_id ) {
			if ( ! is_callable( $this->onSave ) ) {
				return;
			}
			$value = $this->getRequestValue( $attachment_id );
			if ( null != $value ) {
				call_user_func( $this->onSave, $attachment_id, $value );
			}
		} );
	}

	public static function build( string $id, int $priority = 10 ) {
		return new static( $id, $priority );
	}

	protected function getFormName( $post_id ): string {
		return "attachments[{$post_id}][{$this->id}]";
	}

	protected function getRequestValue( $post_id ) {
		if (
			! isset( $_POST["attachments"] ) ||
			! isset( $_POST["attachments"][ $post_id ] ) ||
			! isset( $_POST["attachments"][ $post_id ][ $this->id ] )
		) {
			return null;
		}

		return $_POST["attachments"][ $post_id ][ $this->id ];
	}



	public function label( string $value ): self {
		$this->label = $value;

		return $this;
	}

	public function help( string $value ): self {
		$this->help = $value;

		return $this;
	}

	public function value( callable $fn ): self {
		$this->fnValue = $fn;

		return $this;
	}

	/**
	 * @param string|int $attachment_id
	 *
	 * @return false|mixed
	 */
	public function getValue($attachment_id){
		return call_user_func($this->fnValue, $attachment_id);
	}

	public function onSave( callable $onSave ) {
		$this->onSave = $onSave;

		return $this;
	}

	protected function field( array $field, \WP_Post $post ): array {
		$field["label"] = $this->label;
		$field["helps"] = $this->help;

		return $field;
	}


}