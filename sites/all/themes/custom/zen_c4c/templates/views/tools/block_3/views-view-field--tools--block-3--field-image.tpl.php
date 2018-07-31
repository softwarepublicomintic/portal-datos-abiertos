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

    $link_class = "";
    $link_url = "";
    $link_target = "_parent";

    switch ($row->field_field_tools_data_options_1[0]['rendered']['#markup']) {
        case 'Youtube':
            if (isset($row->field_field_tool_youtube_url[0]['rendered']['#markup'])) {
                $link_url = $row->field_field_tool_youtube_url[0]['rendered']['#markup'];
                $link_class = "youtube-video";
                $link_target = "_blank";
            }
            break;
        case 'URL':
            if (isset($row->field_field_tool_destination_url[0]['rendered']['#markup'])) {
                $link_url = $row->field_field_tool_destination_url[0]['rendered']['#markup'];
                $link_class = "external-url";
                $link_target = "_blank";
            }
            break;
        default:
            if (isset($row->field_field_tool_file[0]['rendered']['#markup'])) {
                $link_url = $row->field_field_tool_file[0]['rendered']['#markup'];
                $link_class = "file-url";
            }
            break;
    }

?>
    <?php if (!empty($link_url)) : ?>
        <a href="<?php echo $link_url; ?>" class="<?php $link_class; ?>" target="<?php echo $link_target;?>"><?php echo $output?></a>
    <?php endif; ?>
