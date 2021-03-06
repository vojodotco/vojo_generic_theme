<?php
// $Id: views-view-fields.tpl.php,v 1.6.2.1 2010/12/04 00:15:14 merlinofchaos Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>

<div class="vojo-group-summary">
  <?php if (!empty($fields['field_group_logo_fid']->raw)): ?>
    <span class="group-logo-listing">
      <?php print $fields['field_group_logo_fid']->content; ?>
    </span>
  <?php endif; ?>
    <h3><?php print $fields['title']->content ?></h3>
    <small><?php print $fields['description']->content ?></small>
    <div class="vojo-group-posts"><?php print $fields['post_count']->content ?> <?php print t('stories'); ?></div>
    <div class="vojo-group-members"><?php print $fields['member_count']->content ?> <?php print t('members'); ?> </div>
</div>
