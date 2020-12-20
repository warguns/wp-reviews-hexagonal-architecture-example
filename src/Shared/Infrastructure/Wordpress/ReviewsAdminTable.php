<?php
/**
 * ReviewsAdminTable
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\Wordpress;

use BetterReview\Review\Domain\Repository\ReviewRepository;
use WP_List_Table;

/**
 * Class ReviewsAdminTable
 *
 * @package BetterReview\Shared\Infrastructure\Wordpress
 */
final class ReviewsAdminTable extends WP_List_Table {
	/**
	 * Count
	 *
	 * @var int
	 */
	private $count;

	/**
	 * Post Names.
	 *
	 * @var array
	 */
	private $post_names;

	/**
	 * ReviewsAdminTable constructor.
	 *
	 * @param array $items items.
	 * @param int   $count count.
	 * @param array $post_names post names.
	 * @param array $args args.
	 */
	public function __construct( array $items = array(), int $count = 0, array $post_names = array(), $args = array() ) {
		parent::__construct( $args );
		$this->items      = $items;
		$this->count      = $count;
		$this->post_names = $post_names;
	}

	/**
	 * Prepare items.
	 */
	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->set_pagination_args(
			array(
				'total_items' => $this->count,
				'per_page'    => ReviewRepository::LIMIT,
			)
		);
		$this->_column_headers = array( $columns, $hidden, $sortable );
	}

	/**
	 * Get Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'         => '<input type="checkbox" />',
			'uuid'       => __( 'Uuid', 'better-reviews' ),
			'post_id'    => __( 'Post Id', 'better-reviews' ),
			'post_name'  => __( 'Post Name', 'better-reviews' ),
			'status'     => __( 'Status', 'better-reviews' ),
			'title'      => __( 'Titulo', 'better-reviews' ),
			'author'     => __( 'Autor', 'better-reviews' ),
			'content'    => __( 'content', 'better-reviews' ),
			'email'      => __( 'Email', 'better-reviews' ),
			'stars'      => __( 'Estrellas', 'better-reviews' ),
			'created_at' => __( 'fecha de creacion', 'better-reviews' ),
			'updated_at' => __( 'fecha de actualizacion', 'better-reviews' ),
		);
	}

	/**
	 * Get Sortable Columns
	 *
	 * @return array[]
	 */
	public function get_sortable_columns(): array {
		return array(
			'uuid'       => array( 'uuid', false ),
			'post_id'    => array( 'post_id', true ),
			'post_name'  => array( 'post_name', true ),
			'status'     => array( 'status', true ),
			'title'      => array( 'title', true ),
			'author'     => array( 'author', true ),
			'content'    => array( 'content', true ),
			'email'      => array( 'email', true ),
			'stars'      => array( 'stars', true ),
			'created_at' => array( 'created_at', true ),
			'updated_at' => array( 'updated_at', true ),
		);
	}

	/**
	 * Column Default
	 *
	 * @param array|object $item item.
	 * @param string       $column_name column name.
	 *
	 * @return mixed|string|true|void
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'uuid':
				$url = wp_nonce_url( 'admin.php?page=edit-review&uuid=' . $item[ $column_name ], 'edit-review' );
				return "<a href='$url'>$item[$column_name]</a>";
			case 'post_id':
			case 'booktitle':
			case 'title':
			case 'author':
			case 'email':
			case 'created_at':
			case 'updated_at':
				return $item[ $column_name ];
			case 'stars':
				$stars = '<div id="reviews"><div class="rating">';
				for ( $i = 5; $i > 0; $i -- ) {
					$checked = ( $i === (int) $item[ $column_name ] ) ? 'checked="checked"' : '';
					$stars  .= '<input type="radio" name="rating' . $item['uuid'] . '-' . $i . '" id="rating-' . $item['uuid'] . '-' . $i . '" value="' . $i . '" required="required" ' . $checked . ' disabled="disabled"><label for="rating-' . $i . '"></label>';
				}
				$stars .= '</div></div>';

				return $stars;
			case 'content':
				return substr( $item[ $column_name ], 0, 130 ) . ( ( $item[ $column_name ] > 130 ) ? '...' : '' );
			case 'status':
				switch ( $item[ $column_name ] ) {
					case 'pending':
						return ucfirst( __( 'pending', 'better-reviews' ) );
					case 'published':
						return ucfirst( __( 'published', 'better-reviews' ) );
					case 'rejected':
						return ucfirst( __( 'rejected', 'better-reviews' ) );
				}
				break;
			case 'post_name':
				return $this->post_names[ $item['post_id'] ];
			default:
				break;
		}
	}

	/**
	 * Get bulk actions
	 *
	 * @return array
	 */
	public function get_bulk_actions(): array {
		return array(
			'bulk-publish' => __( 'Publish', 'better-reviews' ),
			'bulk-delete'  => __( 'Delete', 'better-reviews' ),
		);
	}

	/**
	 * Column CB
	 *
	 * @param array|object $item item.
	 *
	 * @return string|void
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />',
			$item['uuid']
		);
	}
}
