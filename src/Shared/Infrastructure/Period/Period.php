<?php
/**
 * Period
 *
 * @package Shared
 */

namespace HexagonalReviews\Shared\Infrastructure\Period;

/**
 * Class Period
 *
 * @package HexagonalReviews\Shared\Infrastructure\DependencyInjection
 */
final class Period {

	/**
	 * Get period
	 *
	 * @param string $ptime Timestamp.
	 *
	 * @return string
	 */
	public static function get_timeago( string $ptime ): ?string {
		$estimate_time = time() - $ptime;

		if ( $estimate_time < 1 ) {
			return esc_html__( 'less than 1 second ago', 'hexagonal-reviews' );
		}

		$condition = array(
			12 * 30 * 24 * 60 * 60 => array(
				'singular' => esc_html__( 'year', 'hexagonal-reviews' ),
				'plural'   => esc_html__( 'years', 'hexagonal-reviews' ),
			),
			30 * 24 * 60 * 60      => array(
				'singular' => esc_html__( 'month', 'hexagonal-reviews' ),
				'plural'   => esc_html__( 'months', 'hexagonal-reviews' ),
			),
			24 * 60 * 60           => array(
				'singular' => esc_html__( 'day', 'hexagonal-reviews' ),
				'plural'   => esc_html__( 'days', 'hexagonal-reviews' ),
			),
			60 * 60                => array(
				'singular' => esc_html__( 'hour', 'hexagonal-reviews' ),
				'plural'   => esc_html__( 'hours', 'hexagonal-reviews' ),
			),
			60                     => array(
				'singular' => esc_html__( 'minute', 'hexagonal-reviews' ),
				'plural'   => esc_html__( 'minutes', 'hexagonal-reviews' ),
			),
			1                      => array(
				'singular' => esc_html__( 'second', 'hexagonal-reviews' ),
				'plural'   => esc_html__( 'seconds', 'hexagonal-reviews' ),
			),
		);

		foreach ( $condition as $secs => $str ) {
			$d = $estimate_time / $secs;

			if ( $d >= 1 ) {
				$r = round( $d );
				return esc_html__( 'about', 'hexagonal-reviews' ) . ' ' . $r . ' ' . ( $r > 1 ? $str['plural'] : $str['singular'] ) . esc_html__( ' ago.', 'hexagonal-reviews' );
			}
		}
	}
}