<?php
/**
 * This is a bunch of custom form code to show a much prettier story create form within the group
 * Extensive use of suggestions at:
 *   http://drupal.org/node/601646
 **/

// remove some other buttons
$group = og_get_group_context();
unset($form['buttons']['preview']);
?>
<div class="row vojo-submit-form">
    
    <?php print drupal_render($form['title']); ?>

    <?php print drupal_render($form['taxonomy']); ?>
    
    <?php print drupal_render($form['body_field']); ?>

    <?php print drupal_render($form['language']); ?>
    
    <?php print drupal_render($form['field_map']); ?>

    <?php print drupal_render($form['attachments']); ?>
    
    <input type="hidden" name="og_groups[<?=$group->nid?>]" id="edit-og-groups-<?=$group->nid?>" value="<?=$group->nid?>">

    <div class="twelvecol last vojo-buttons">
        <?php print drupal_render($form); ?>
    </div>

</div>

<?php
