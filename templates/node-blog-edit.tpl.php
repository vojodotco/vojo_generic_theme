<?php
/**
 * This is a bunch of custom form code to show a much prettier story create form within the group
 * Extensive use of suggestions at:
 *   http://drupal.org/node/601646
 **/

if (array_key_exists('translation',$_GET) ){
  // handle the case of translating a node
  $original_nid = $_GET['translation'];
  if( is_numeric($original_nid) ){
    $original_node = node_load($original_nid);
    $group = og_determine_context_get_group($original_node);
  }
} else {
  // get the group like normal
  $group = og_get_group_context();
}

if( vojo_og_can_submit($group->nid) ) {

    unset($form['buttons']['preview']);
    ?>
    <div class="row vojo-submit-form">
        
        <?php print drupal_render($form['title']); ?>
    
        <?php print drupal_render($form['language']); ?>
        
        <?php print drupal_render($form['body_field']); ?>
            
        <?php print drupal_render($form['attachments']); ?>

        <?php print drupal_render($form['field_map']); ?>
    
        <?php print drupal_render($form['taxonomy']); ?>

        <?php if ($group) { ?>
          <input type="hidden" name="og_groups[<?=$group->nid?>]" id="edit-og-groups-<?=$group->nid?>" value="<?=$group->nid?>">
        <?php } ?>
    
        <div class="twelvecol last vojo-buttons">
            <?php print drupal_render($form); ?>
        </div>
    
    </div>
    
    <?php

} else {
    ?>

    <h1>Error</h1>

    <?
}