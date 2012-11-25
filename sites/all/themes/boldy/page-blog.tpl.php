<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir; ?>">
  <head>
    <?php print $head; ?>
    <title><?php print $head_title; ?></title>
    <?php print $styles; ?>
    <?php print $scripts; ?>
    <!--[if lt IE 7]>
    <?php print boldy_get_ie_styles(); ?>
    <![endif]-->
  </head>
  <body class="<?php print $body_classes; ?>">
    <div id="mainWrapper"> <!-- BEGIN MAINWRAPPER -->
      <div id="wrapper"> <!-- BEGIN WRAPPER -->
        <div id="header"> <!-- BEGIN HEADER -->
          <?php
          // Prepare header
          $site_fields = array();
          if ($site_name) {
            $site_fields[] = check_plain($site_name);
          }
          if ($site_slogan) {
            $site_fields[] = check_plain($site_slogan);
          }
          $site_title = implode(' ', $site_fields);
          if ($site_fields) {
            $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
          }
          $site_html = implode(' ', $site_fields);

          if ($logo || $site_title) {
            print '<div id="logo"><p class="page-title">';
            if ($logo) {
              print '<a href="' . check_url($front_page) . '" title="' . $site_title . '"><img src="' . check_url($logo) . '" alt="' . $site_title . '" /></a>';
            }
            print '<a href="' . check_url($front_page) . '" title="' . $site_title . '">' . $site_html . '</a></p></div>';
          }
          ?>
          <!-- BEGIN MAIN MENU -->
          <div id="mainMenu">
            <?php if (isset($primary_links)): ?>
              <?php print theme('links', $primary_links, array('class' => 'links primary-links')); ?>
            <?php endif; ?>
            <?php if (isset($nav_bar)): ?>
              <?php print $nav_bar; ?>
            <?php endif; ?>
          </div>
          <!-- END MAIN MENU -->
          <?php if ($search_box): ?>
            <div id="topSearch"> <!-- BEGIN TOP SEARCH -->
              <?php print $search_box; ?>
            </div> <!-- END TOP SEARCH -->
          <?php endif; ?>
          <div id="topSocial"> <!-- BEGIN TOP SOCIAL LINKS -->
            <ul>
              <?php if (theme_get_setting('boldy_linkedin_link') != ""): ?>
                <li><a href="<?php print theme_get_setting('boldy_linkedin_link'); ?>" class="linkedin" title="Join us on LinkedIn!"><img src="/<?php print drupal_get_path('theme', 'boldy'); ?>/images/ico_linkedin.png" alt="LinkedIn" /></a></li>
              <?php endif; ?>
              <?php if (theme_get_setting('boldy_twitter_user') != ""): ?>
                <li><a href="http://www.twitter.com/<?php print theme_get_setting('boldy_twitter_user'); ?>" class="twitter" title="Follow Us on Twitter!"><img src="/<?php print drupal_get_path('theme', 'boldy'); ?>/images/ico_twitter.png" alt="Follow Us on Twitter!" /></a></li>
              <?php endif; ?>
              <?php if (theme_get_setting('boldy_facebook_link') != ""): ?>
                <li><a href="<?php print theme_get_setting('boldy_facebook_link'); ?>" class="twitter" title="Join Us on Facebook!"><img src="/<?php print drupal_get_path('theme', 'boldy'); ?>/images/ico_facebook.png" alt="Join Us on Facebook!" /></a></li>
              <?php endif; ?>
              <li><a href="/rss" title="RSS" class="rss"><img src="/<?php print drupal_get_path('theme', 'boldy'); ?>/images/ico_rss.png" alt="Subcribe to Our RSS Feed" /></a></li>
            </ul>
          </div> <!-- END TOP SOCIAL LINKS -->
        </div> <!-- END HEADER -->
        <div id="content"> <!-- BEGIN CONTENT -->
          <?php if ($slider): ?>
            <div id="slider-wrapper">
              <div id="slider"> <!-- BEGIN SLIDER -->
                <?php print $slider; ?>
              </div> <!-- END SLIDER -->
            </div>
          <?php endif; ?>
          <div id="colLeft"> <!-- Begin #colLeft -->
            <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">';
            endif; ?>
            <?php if ($title): print '<div id="archive-title">Browsing articles in "<strong>' . $title . '</strong>"</div>';
            endif; ?>
            <?php if ($tabs): print '<ul class="tabs primary">' . $tabs . '</ul></div>';
            endif; ?>
            <?php if ($tabs2): print '<ul class="tabs secondary">' . $tabs2 . '</ul>';
            endif; ?>
              <?php if ($show_messages && $messages): print $messages;
              endif; ?>
            <?php print $help; ?>
              <?php if ($content_top): ?>
              <div id="content-top" class="region region-content_top">
              <?php print $content_top; ?>
              </div> <!-- /#content-top -->
            <?php endif; ?>
            <div id="content-area" class="clear-block">
            <?php print $content; ?>
            </div>
              <?php if ($feed_icons): ?>
              <div class="feed-icons"><?php print $feed_icons; ?></div>
            <?php endif; ?>
          <?php if ($content_bottom): ?>
              <div id="content-bottom" class="region region-content_bottom">
              <?php print $content_bottom; ?>
              </div> <!-- /#content-bottom -->
          <?php endif; ?>
          </div> <!-- End #colLeft -->
<?php if ($right): ?>
            <div id="colRight"> <!-- Begin #colRight -->
          <?php print $right; ?>
            </div> <!-- End #colRight -->
<?php endif; ?>
        </div> <!-- END CONTENT -->
      </div> <!-- END WRAPPER -->
      <div id="footer"> <!-- BEGIN FOOTER -->
            <?php if (theme_get_setting('boldy_footer_actions')): ?>
          <div style="width:960px; margin: 0 auto; position:relative;">
            <a href="#" id="showHide" <?php if (theme_get_setting('boldy_actions_hide') == "hidden"): print 'style="background-position:0 -16px"';
              endif; ?>>Show/Hide Footer Actions</a>
          </div>
          <div id="footerActions" <?php if (theme_get_setting('boldy_actions_hide') == "hidden"): print 'style="display:none"';
              endif; ?>>
            <div id="footerActionsInner">
  <?php if (theme_get_setting('boldy_twitter_user') != "" && theme_get_setting('boldy_latest_tweet')): ?>
                <div id="twitter">
                  <a href="http://twitter.com/<?php print theme_get_setting('boldy_twitter_user'); ?>" class="action">Follow Us!</a>
                  <div id="latest">
                    <div id="tweet">
                      <div id="twitter_update_list"></div>
                    </div>
                    <div id="tweetBottom"></div>
                  </div>
                </div>
  <?php endif; ?>
              <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
              <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php print theme_get_setting('boldy_twitter_user'); ?>.json?callback=twitterCallback2&amp;count=<?php
  if (theme_get_setting('boldy_number_tweets') != "") {
    print theme_get_setting('boldy_number_tweets');
  }
  else {
    print "1";
  }
  ?>">
              </script>
  <?php if ($footer_actions): ?>
                <div id="quickContact">
                <?php print $footer_actions; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
              <?php endif; ?>
        <div id="footerWidgets">
          <div id="footerWidgetsInner">
<?php if ($footer): ?>
  <?php print $footer; ?>
<?php endif; ?>
    <?php if ($footer_message): ?>
              <div id="copyright"> <!-- BEGIN COPYRIGHT -->
  <?php print $footer_message; ?>
              </div> <!-- END COPYRIGHT -->
<?php endif; ?>
          </div>
        </div>
      </div> <!-- END FOOTER -->
    </div> <!-- END MAINWRAPPER -->
<?php print $closure; ?>
  </body>
</html>