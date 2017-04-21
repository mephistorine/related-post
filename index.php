<?php

/*
	Plugin Name: Похожжие записи
	Description: Этот плагин показывает похожие посты по категориям
	Version: 1.0
	Author: stylesam
	Author URI: http://stylesam.com
*/

class SLSMRelatedPost
{
	/**
	 * SLSMRelatedPost constructor.
	 */
	public function __construct()
	{
		add_filter('the_content', [$this, 'slsm_related_post']);
	}

	/**
	 * @param $content
	 * @return mixed
	 */
	public function slsm_related_post($content)
	{
		if( !is_single() )
		{
			return $content;
		}

		$id = get_the_ID();
		$categories = get_the_category( $id );
		foreach ($categories as $category)
		{
			$cats_id[] = $category->cat_ID;
		}

		$related_posts = new WP_Query([
			'post_per_page' => 5,
			'category__in' => $cats_id,
			'orderby' => 'rand',
			'post__not_in' => [$id]
		]);

		if( $related_posts->have_posts() )
		{
			$content .= '<div class="related-post"><h3>Возможно вас заинтересует</h3>';
			while ( $related_posts->have_posts() )
			{
				$related_posts->the_post();

				if (has_post_thumbnail())
				{
					$img = get_the_post_thumbnail(
						get_the_ID(),
						[100, 100],
						[
							'alt' => get_the_title(),
							'title' => get_the_title()
						]
					);
				}
				else
				{
					$img = '<img src="' . plugins_url('/images/no_img.jpg', __FILE__) . '" alt="' . get_the_title() . '" >';
				}
				$content .= '<a href="' . get_permalink() . '">' . $img . '</a>';
			}
			$content .= '</div>';
			wp_reset_query();
		}

		return $content;
	}

}

$slsm_related_post = new SLSMRelatedPost();