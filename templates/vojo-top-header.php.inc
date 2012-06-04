
<a id="beta" href="https://dev.vozmob.net/projects/cronicas/roadmap">beta</a>
<div id="vojo-top-header">
  <div class="container">
    <div class="row">
      <div class="span8">
        <a  id="vojo-logo" href="<?php print $base_path; ?>"><img src="/<?php print drupal_get_path('theme','vojo_generic'); ?>/images/vojo/logo-top-header.png" alt="Vojo home page"/></a>
         <?php if ($vojo_callin_number): ?>
           <div class="vojo-callin"><?php print '<strong>'. t('Call in your story: ') .'</strong>'. $vojo_callin_number; ?></div>
         <?php endif; ?>
         <?php print $header ?>
      </div> <!-- /.span8 -->
      <div class="span4">
        <?php print $user_links; ?>
      </div> <!-- /.span4 -->
    </div> <!-- /.row -->
  </div> <!-- /.container -->
</div> <!-- /#vojo-top-header -->
