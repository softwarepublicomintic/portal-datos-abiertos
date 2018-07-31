<?php

print "Plantilla 2";

$content = $variables['content'];
 $output = '';
print '<p clas="bodycillo">';
 echo render($form['field_body']);
print '</p>';
 if ($content) {
     $output = '<dl class="node-type-use">';
     foreach ($content as $item) {
       $output .= '<dt>' . l($item['title'], $item['href'], $item['localized_options']) . '</dt>';
       $output .= '<dd>' . filter_xss_admin($item['description']) . '</dd>';
     }
     $output .= '</dl>';
   }
   else {
     $output = '<p>' . t('You have not created any content types yet. Go to the <a href="@create-content">content type creation page</a> to add a new content type.', array('@create-content' => url('admin/structure/types/add'))) . '</p>';
   }

print $output;

   return $output;

 ?>
