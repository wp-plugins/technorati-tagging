============== Technorati Tagging Plugin ==============
Tags: technorati, tagging, tag
Contributors: boneill

Technorati Tagging allows you to add Techorati <http://www.technorati.com> tags to your posts.  It also allows you to create a tag frequency blob that displays your most frequently used tags.  The tags change size and colour depending on how frequently each tag is used.

== Installation ==

1. Put the technoratitagging.php file into your "wp-content/plugins" directory.
2. Activate the plugin on the plugin screen.
3. Goto Options -> Permalinks to update the rewrite rules (YOU DO NOT NEED TO CHANGE ANYTHING HERE!)
4. Goto Plugins -> Technorati Tagging and configure the plugin.
5. Insert <?php get_TagsBit(); ?> into one of your templates (sidebar.php is recommended).

== Usage ==

This plugin allows you to easily link to Technorati and
display a tag grid (similar to as can be found on 
http://radar.oreilly.com/ and http://www.technorati.com/tag/)

To display the tag grid in your page add:
<?php get_TagsBit(); ?>

It is designed to be placed in the sidebar.

There are a number of settings at the top of the PHP file
that you should check and set to your personal values.

To add tags to your post, add a custom field called "technorati"
or "ttaglist" and enter comma seperated values, e.g.

tag,test,fish,monkeys
