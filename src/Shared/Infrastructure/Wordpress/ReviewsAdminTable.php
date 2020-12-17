<?php

declare(strict_types=1);

namespace BetterReview\Shared\Infrastructure\Wordpress;

use BetterReview\Review\Domain\Repository\ReviewRepository;

final class ReviewsAdminTable extends \WP_List_Table
{
    /** @var int */
    private $count;

    /** @var array */
    private $postNames;

    public function __construct(array $items = [], int $count = 0, array $postNames,  $args = array())
    {
        parent::__construct($args);
        $this->items = $items;
        $this->count = $count;
        $this->postNames = $postNames;
    }

    public function get_columns() {
        $columns = array (
            'cb'      => '<input type="checkbox" />',
            'uuid' => __('Uuid','better-reviews'),
            'post_id' => __('Post Id','better-reviews'),
            'post_name' => __('Post Name','better-reviews'),
            'status' => __('Status', 'better-reviews'),
            'title' =>  __('Titulo','better-reviews'),
            'author' =>  __('Autor','better-reviews'),
            'content' =>  __('content','better-reviews'),
            'email' =>  __('Email','better-reviews'),
            'stars' =>  __('Estrellas','better-reviews'),
            'created_at' =>  __('fecha de creacion','better-reviews'),
            'updated_at' =>  __('fecha de actualizacion','better-reviews'),
        );

        return $columns;
    }


    function getSortableColumns() {
        $columns = array (
            'uuid' => ['uuid', false],
            'post_id' => ['post_id', true],
            'post_name' => ['post_name', true],
            'status' => ['status', true],
            'title' => ['title', true],
            'author' => ['author', true],
            'content' => ['content', true],
            'email' => ['email', true],
            'stars' => ['stars', true],
            'created_at' => ['created_at', true],
            'updated_at' => ['updated_at', true],
        );

        return $columns;
    }

    function prepare_items () {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->getSortableColumns();
        $this->set_pagination_args([
            'total_items' => $this->count,
            'per_page'    => ReviewRepository::LIMIT
        ]);
        $this->_column_headers = array( $columns , $hidden , $sortable );
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'uuid':
                return "<a href='admin.php?page=edit-review&uuid=$item[$column_name]'>$item[$column_name]</a>";
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
                for ($i=5;$i>0;$i--) {
                    $checked = ($i === (int) $item[$column_name]) ? 'checked="checked"': '';
                    $stars .= '<input type="radio" name="rating' . $item['uuid'] . '-' . $i . '" id="rating-' . $item['uuid'] . '-' . $i . '" value="' . $i . '" required="required" ' . $checked . ' disabled="disabled"><label for="rating-' . $i . '"></label>';
                }
                $stars .= '</div></div>';
                return $stars;
            case 'content':
                return  substr($item[ $column_name ], 0, 130) . (($item[$column_name] > 130) ? '...' : '');
            case 'status':
                return ucfirst(__($item[$column_name], 'better-reviews'));
            case 'post_name':
                return $this->postNames[$item['post_id']];
            default:
                return print_r( $item, true ) ;
        }
    }

    public function get_bulk_actions() {
        $actions = [
            'bulk-publish' => __('Publish', 'better-reviews'),
            'bulk-delete' => __('Delete', 'better-reviews'),
        ];

        return $actions;
    }

    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['uuid']
        );
    }

    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
                echo "hola";
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_customer( absint( $_GET['customer'] ) );

                wp_redirect( esc_url( add_query_arg() ) );
                exit;
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_customer( $id );

            }

            wp_redirect( esc_url( add_query_arg() ) );
            exit;
        }
    }
}