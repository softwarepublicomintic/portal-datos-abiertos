<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */

	$alt_text = t("Show Video");
	$video_url = "";
	if (!empty($output)) {
		$video_array = explode("v=", $output);

		if (!empty($video_array[1])) {
			// Get youtube video image
			$video_url = "https://img.youtube.com/vi/".$video_array[1]."/3.jpg?fid=".$row->field_collection_item_field_data_field_tools_video_carousel_;
		}
	}

	if (!empty($row->field_field_tool_title[0]['rendered']['#markup'])) {
		$alt_text = $row->field_field_tool_title[0]['rendered']['#markup'];
	}
?>
<!-- Modificación para evitar links rotos - 27/03/2018 Pablo Villate MyQ -->
<!--<a href="/video/<?php echo $row->field_collection_item_field_data_field_tools_video_carousel_; ?>"><img src="<?php echo $video_url; ?>" alt="<?php echo $alt_text; ?>"/></a>-->
<a href="#" data-url="/video/<?php echo $row->field_collection_item_field_data_field_tools_video_carousel_; ?>"><img src="<?php echo $video_url; ?>" alt="<?php echo $alt_text; ?>"/></a>
