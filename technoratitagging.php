<?php
/*
Plugin Name: Technorati Tagging
Plugin URI: http://boneill.ninjagrapefruit.com/wp-tag-plugin/
Description: Allows the use of Technorati tags in pages and blogs entries.  Allows you to have a tag grid with a list of your tags and often often they're used.
Version: 1.6
Author: Ben O'Neill
Author URI: http://boneill.ninjagrapefruit.com/
*/

/*
Released under the Creative Commons Attribution-ShareAlike
license. For more information visit:
http://creativecommons.org/licenses/by-sa/2.0/
*/

if (!function_exists('bon_technoratiTaggingLoadOptions'))
{
    function bon_technoratiTaggingLoadOptions($loaddefaults = false)
    {
        // prepare default options
        $defaults['technoTag_link'] = "http://www.technorati.com/tag/";
        $defaults['technoTag_prefix'] = '<div class="ttag">tags: ';
        $defaults['technoTag_suffix'] = '</div>';
        $defaults['technoTag_format'] = '<a href="%URL%" rel="tag">%TAG%</a>';
        $defaults['technoTag_seperator'] = ', ';
        $defaults['technoTag_largest_fontsize'] = 24;
        $defaults['technoTag_smallest_fontsize'] = 10;
        $defaults['technoTag_largest_colour'] = '#335588';
        $defaults['technoTag_smallest_colour'] = '#e2e2c8';
        $defaults['technoTag_tagbits_count'] = 30;
        $defaults['technoTag_posts_in_archive'] = 15;
        $defaults['technoTag_tagbitlink'] = "/tag/";
        $defaults['technoTag_tagbit_numposts'] = 0;
        $defaults['technoTag_tagbit_numdays'] = 62;
        $defaults['technoTag_rewrite_catch'] = 'tag/([0-9a-zA-Z+_-]+)/?';

        if ($loaddefaults)
        {
            $options = $defaults;
        }
        else
        {
            $options = get_option('bon_technoratiTagging');

            if ($options === false)
                $options = $defaults;
            else
            {
                // remove evil slashes
                $options['technoTag_link'] = stripslashes($options['technoTag_link']);
                $options['technoTag_prefix'] = stripslashes($options['technoTag_prefix']);
                $options['technoTag_suffix'] = stripslashes($options['technoTag_suffix']);
                $options['technoTag_format'] = stripslashes($options['technoTag_format']);
                $options['technoTag_seperator'] = stripslashes($options['technoTag_seperator']);
                $options['technoTag_rewrite_catch'] = stripslashes($options['technoTag_rewrite_catch']);
            }
        }


        return $options;
    }
}

if (is_plugin_page())
{
    // display the options page

    if (isset($_POST['update_techno_settings']))
    {
        $new_options = array('technoTag_link' => $_POST['link'],
                             'technoTag_prefix' => $_POST['prefix'],
                             'technoTag_suffix' => $_POST['suffix'],
                             'technoTag_format' => $_POST['format'],
                             'technoTag_seperator' => $_POST['seperator'],
                             'technoTag_tagbitlink' => $_POST['tagbitlink'],
                             'technoTag_tagbits_count' => (int) $_POST['tagbits_count'],
                             'technoTag_largest_fontsize' => (int) $_POST['largest_fontsize'],
                             'technoTag_largest_colour' => $_POST['largest_colour'],
                             'technoTag_smallest_fontsize' => (int) $_POST['smallest_fontsize'],
                             'technoTag_smallest_colour' => $_POST['smallest_colour'],
                             'technoTag_tagbit_numposts' => (int) $_POST['tagbit_numposts'],
                             'technoTag_tagbit_numdays' => (int) $_POST['tagbit_numdays'],
                             'technoTag_posts_in_archive' => (int) $_POST['posts_in_archive'],
                             'technoTag_rewrite_catch' => $_POST['rewrite_catch']);

        update_option('bon_technoratiTagging', $new_options);

        ?> <div class="updated"><p>Options changes saved.</p></div> <?php
    }

    // load the options
    $options  = bon_technoratiTaggingLoadOptions();
    $defaults = bon_technoratiTaggingLoadOptions(true);

?>

        <div class="wrap">
                <h2>Technorati Tagging Options</h2>

                <form method="post">
            <input type="hidden" name="update_techno_settings" value="update" />

            <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
                <tr valign="top"> 
                    <th width="33%" scope="row">External Tag Resource:</th> 
                    <td>
                        <input name="link" type="text" id="link" value="<?php echo $options['technoTag_link']; ?>" size="40" /><br/>
                        Recommended: <code><?php echo $defaults['technoTag_link']; ?></code>
                    </td> 
                </tr>
                <tr valign="top"> 
                    <th width="33%" scope="row">Tag Prefix:</th> 
                    <td>
                        <input name="prefix" type="text" id="prefix" value="<?php echo htmlentities($options['technoTag_prefix']); ?>" size="40" /><br/>
                        This is outputted just before your tags, for example "<code><?php echo htmlentities($defaults['technoTag_prefix']); ?></code>"
                    </td> 
                </tr>
                <tr valign="top"> 
                    <th width="33%" scope="row">Tag Suffix:</th> 
                    <td>
                        <input name="suffix" type="text" id="suffix" value="<?php echo htmlentities($options['technoTag_suffix']); ?>" size="40" /><br/>
                        This is outputted just after your tags, for example "<code><?php echo htmlentities($defaults['technoTag_suffix']); ?></code>"
                    </td> 
                </tr>
                <tr valign="top"> 
                    <th width="33%" scope="row">Tag Format:</th> 
                    <td>
                        <input name="format" type="text" id="format" value="<?php echo htmlentities($options['technoTag_format']); ?>" size="40" /><br/>
                        This sets how your tags are displayed, there are two tags available for your use, <code>%URL</code> and <code>%TAG</code>.<br/>
                        Recommended: "<code><?php echo htmlentities($defaults['technoTag_format']); ?></code>"
                    </td> 
                </tr>
                <tr valign="top"> 
                    <th width="33%" scope="row">Tag Seperator:</th> 
                    <td>
                        <input name="seperator" type="text" id="seperator" value="<?php echo htmlentities($options['technoTag_seperator']); ?>" size="20" /><br/>
                        This is outputted between each of the tags, for example: "<code><?php echo htmlentities($defaults['technoTag_seperator']); ?></code>"
                    </td> 
                </tr>
            </table>

            <fieldset class="options">
                <legend>Tag Frequency Blob</legend>

                <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
                    <tr valign="top"> 
                        <th width="33%" scope="row">Tags Link to:</th> 
                        <td>
                            <input name="tagbitlink" type="text" id="tagbitlink" value="<?php echo $options['technoTag_tagbitlink']; ?>" size="40" /><br/>
                            Recommended: <code><?php echo $defaults['technoTag_tagbitlink']; ?></code>
                        </td> 
                    </tr>
                    <tr valign="top"> 
                        <th width="33%" scope="row">Tags to Show:</th> 
                        <td>
                            <input name="tagbits_count" type="text" id="tagbits_count" value="<?php echo $options['technoTag_tagbits_count']; ?>" size="40" /><br/>
                            The top <code>x</code> tags are displayed in the tag frequency blob.
                        </td> 
                    </tr>
                    <tr valign="top"> 
                        <th width="33%" scope="row">Largest Tag Font Size:</th> 
                        <td>
                            <input name="largest_fontsize" type="text" id="largest_fontsize" value="<?php echo $options['technoTag_largest_fontsize']; ?>" size="10" />px<br/>
                            The most frequently used tag will be this size, no tag will be larger than this.
                        </td> 
                    </tr>
                    <tr valign="top"> 
                        <th width="33%" scope="row">Largest Tag Colour:</th> 
                        <td>
                            <input name="largest_colour" type="text" id="largest_colour" value="<?php echo $options['technoTag_largest_colour']; ?>" size="10" /><br/>
                            <strong>It is very important that the colour be a full HTML colour, e.g. <em>#123456</em></strong>.  The largest tag will be this colour, other tags will be a gentle fade from this colour (most frequently used tags) to the smallest tag colour (option lower down).
                        </td> 
                    </tr>
                    <tr valign="top"> 
                        <th width="33%" scope="row">Smallest Tag Font Size:</th> 
                        <td>
                            <input name="smallest_fontsize" type="text" id="smallest_fontsize" value="<?php echo $options['technoTag_smallest_fontsize']; ?>" size="10" />px<br/>
                            No tag will be smaller than this.
                        </td> 
                    </tr>
                    <tr valign="top"> 
                        <th width="33%" scope="row">Smallest Tag Colour:</th> 
                        <td>
                            <input name="smallest_colour" type="text" id="smallest_colour" value="<?php echo $options['technoTag_smallest_colour']; ?>" size="10" /><br/>
                            <strong>It is very important that the colour be a full HTML colour, e.g. <em>#123456</em></strong>.  Tags will be a gentle fade from this colour (least frequently used tags) to the largest tag colour (option higher up).
                        </td> 
                    </tr>
                    <tr valign="top"> 
                        <th width="33%" scope="row">Tags from Posts:</th> 
                        <td>
                            This option allows you to configure which posts are used to generate the tags displayed in the tag frequency blob.  You may configure the system to use a certain number of posts, or posts up to a certain age -- or both.<br/><br/>

                            <span style="font-weight: bold;">Number of Posts:</span><br/>
                            <input name="tagbit_numposts" type="text" id="tagbit_numposts" value="<?php echo $options['technoTag_tagbit_numposts']; ?>" size="10" /> posts<br/><br/>

                            <span style="font-weight: bold;">Number of Days:</span><br/>
                            <input name="tagbit_numdays" type="text" id="tagbit_numdays" value="<?php echo $options['technoTag_tagbit_numdays']; ?>" size="10" /> days
                        </td> 
                    </tr>
                </table>
            </fieldset>

            <fieldset class="options">
                <legend>Tag Post Listing Page</legend>

                <p>These options configure how the tag listing page will look.  This is the page that the user is taken to when they click on a tag in your tag frequency blob.</p>

                <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
                    <tr valign="top"> 
                        <th width="33%" scope="row">Posts to Show:</th> 
                        <td>
                            <input name="posts_in_archive" type="text" id="posts_in_archive" value="<?php echo $options['technoTag_posts_in_archive']; ?>" size="10" /><br/>
                            The number of posts to show that have the tag the user selected.
                        </td> 
                    </tr>
                </table>
            </fieldset>

                    <p class="submit">
                <input type="submit" name="Submit" value="Update Options &raquo;" />
            </p>

            <fieldset class="options">
                <legend>Advanced Options</legend>

                <p>This is a very advanced option and should not be changed if you are not 100% certain you know what you are doing and how it will effect WordPress.</p>

                <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
                    <tr valign="top"> 
                        <th width="33%" scope="row">Tag Rewrite Rule:</th> 
                        <td>
                            <input name="rewrite_catch" type="text" id="rewrite_catch" value="<?php echo htmlentities($options['technoTag_rewrite_catch']); ?>" size="40" /><br/>
                            Recommended: <code><?php echo htmlentities($defaults['technoTag_rewrite_catch']); ?></code>
                        </td> 
                    </tr>
                </table>
            </fieldset>

                    <p class="submit">
                <input type="submit" name="Submit" value="Update Options &raquo;" />
            </p>
        </form>       
    </div>

<?php

}
else
{

// main app is here

// we use this so we don't have to keep working out hexdec() on the colours
$bon_TechnoratiTagging_colourcache = array();

/**
 * Generates the tags bit
 */
function get_TagsBit()
{
    global $wpdb;

    $bon_TechnoTaggingOptions = bon_technoratiTaggingLoadOptions();

    if ($technoTag_tagbitlink == '')
    {
        $technoTag_tagbitlink = $bon_TechnoTaggingOptions['technoTag_link'];
    }

    $where = '';
    $limit = '';

    if ($bon_TechnoTaggingOptions['technoTag_tagbit_numdays'] > 0)
    {
        $where = " AND `post_date` >= '" . date('Y-m-d H:i:00', time() - ($bon_TechnoTaggingOptions['technoTag_tagbit_numdays'] * 86400)) . "'";
    }

    if ($bon_TechnoTaggingOptions['technoTag_tagbit_numposts'] > 0)
    {
        $limit = ' LIMIT ' . $bon_TechnoTaggingOptions['technoTag_tagbit_numposts'];
    }

    // load the tags
    $tagRawData = $wpdb->get_col("SELECT `meta_value` FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID=$wpdb->postmeta.post_id WHERE (`meta_key`='ttaglist' OR `meta_key`='technorati') AND `post_status`='publish'$where ORDER BY `post_date` DESC$limit");

    if (count($tagRawData) == 0)
    {
        return;
    }

    $tagData  = implode(",", $wpdb->get_col("SELECT `meta_value` FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID=$wpdb->postmeta.post_id WHERE (`meta_key`='ttaglist' OR `meta_key`='technorati') AND `post_status`='publish'$where ORDER BY `post_date` DESC$limit"));
    $tagArray = preg_split("/[,]+/", $tagData);

    $tagsFreqData = array();

    // work out the frequency of each of the tags
    foreach ($tagArray as $tag)
    {
        $tag = trim(strtolower($tag));
        if ($tag != '')
        {
            if (!isset($tagsFreqData[$tag]))
                $tagsFreqData[$tag] = 1;
            else
                $tagsFreqData[$tag]++;
        }
    }

    // sort into order
    arsort($tagsFreqData, SORT_NUMERIC);

    // get the min/max frequency
    $freqMin = end($tagsFreqData);
    $freqMax = reset($tagsFreqData);

    // make sure we have the right number of tags
    if ($bon_TechnoTaggingOptions['technoTag_tagbits_count'] > 0)
    {
        if (count($tagsFreqData) > $bon_TechnoTaggingOptions['technoTag_tagbits_count'])
           $tagsFreqData = array_slice($tagsFreqData, 0, $bon_TechnoTaggingOptions['technoTag_tagbits_count']);
    }

    // get the names of the tags in alphabetical order, used to output
    // the list in alphabetical order.
    $tagNames = array_keys($tagsFreqData);
    sort($tagNames);

    $tagDoneData = array();

    foreach ($tagNames as $tag)
    {
        $size = bon_technoratiTagging_get_tagbit_fontsize($tagsFreqData[$tag], $freqMax, $freqMin);
        $colour = bon_technoratiTagging_get_tagbit_color($tagsFreqData[$tag], $freqMax, $freqMin);

        $tagFixed = bon_technorati_fixTag($tag);

        $tagDoneData[] = ' <a href="' . $bon_TechnoTaggingOptions['technoTag_tagbitlink'] . $tagFixed . '" style="font-size: ' . $size . 'px; color: ' . $colour . '; text-decoration: none;">' . $tag . '</a> ';
    }

    echo '<div style="text-align: center; font-family: arial, sans-serif; line-height: ' . $bon_TechnoTaggingOptions['technoTag_largest_fontsize'] . 'px;">' . implode('', $tagDoneData) . '</div>';

} // get_TagsBit

function bon_technoratiTagging_get_tagbit_color($freq, $max_freq, $min_freq)
{
    global $bon_TechnoratiTagging_colourcache;
    $bon_TechnoTaggingOptions = bon_technoratiTaggingLoadOptions();

    if ($max_freq == $min_freq)
    {
        // all tags used with same frequency
        return $bon_TechnoTaggingOptions['technoTag_largest_colour'];
    }

    if (!isset($bon_TechnoratiTagging_colourcache['large']))
    {
        $bon_TechnoratiTagging_colourcache['large']['r'] = hexdec(substr($bon_TechnoTaggingOptions['technoTag_largest_colour'], 1, 2));
        $bon_TechnoratiTagging_colourcache['large']['g'] = hexdec(substr($bon_TechnoTaggingOptions['technoTag_largest_colour'], 3, 2));
        $bon_TechnoratiTagging_colourcache['large']['b'] = hexdec(substr($bon_TechnoTaggingOptions['technoTag_largest_colour'], 5, 2));
    }

    if (!isset($bon_TechnoratiTagging_colourcache['small']))
    {
        $bon_TechnoratiTagging_colourcache['small']['r'] = hexdec(substr($bon_TechnoTaggingOptions['technoTag_smallest_colour'], 1, 2));
        $bon_TechnoratiTagging_colourcache['small']['g'] = hexdec(substr($bon_TechnoTaggingOptions['technoTag_smallest_colour'], 3, 2));
        $bon_TechnoratiTagging_colourcache['small']['b'] = hexdec(substr($bon_TechnoTaggingOptions['technoTag_smallest_colour'], 5, 2));
    }

    $percentColour = ($freq - $min_freq) / ($max_freq - $min_freq);

    // R
    $colourAsFloat = $percentColour * ($bon_TechnoratiTagging_colourcache['large']['r'] - $bon_TechnoratiTagging_colourcache['small']['r']) + $bon_TechnoratiTagging_colourcache['small']['r'];
    $colour_R = ceil($colourAsFloat);

    // G
    $colourAsFloat = $percentColour * ($bon_TechnoratiTagging_colourcache['large']['g'] - $bon_TechnoratiTagging_colourcache['small']['g']) + $bon_TechnoratiTagging_colourcache['small']['g'];
    $colour_G = ceil($colourAsFloat);

    // B
    $colourAsFloat = $percentColour * ($bon_TechnoratiTagging_colourcache['large']['b'] - $bon_TechnoratiTagging_colourcache['small']['b']) + $bon_TechnoratiTagging_colourcache['small']['b'];
    $colour_B = ceil($colourAsFloat);

    return '#' . dechex($colour_R) . dechex($colour_G) . dechex($colour_B);

} // technoratiTagging_get_tagbit_color

function bon_technoratiTagging_get_tagbit_fontsize($freq, $max_freq, $min_freq)
{
    $bon_TechnoTaggingOptions = bon_technoratiTaggingLoadOptions();

    if ($max_freq == $min_freq)
    {
        // all tags used with same frequency
        return $bon_TechnoTaggingOptions['technoTag_largest_fontsize'];
    }

    $percentageOfFontsize = ($freq - $min_freq) / ($max_freq - $min_freq);
    $fontsizeAsFloat = $percentageOfFontsize * ($bon_TechnoTaggingOptions['technoTag_largest_fontsize'] - $bon_TechnoTaggingOptions['technoTag_smallest_fontsize']) + $bon_TechnoTaggingOptions['technoTag_smallest_fontsize'];
    $fontsizeAsPx = ceil($fontsizeAsFloat);

    return $fontsizeAsPx;

} // technoratiTagging_get_tagbit_fontsize

/**
 * Uses the custom values from the post to generate a list of
 * tags that are put after the post.
 */
function bon_technoratiTagging($text)
{
    $bon_TechnoTaggingOptions = bon_technoratiTaggingLoadOptions();

    // variable initialisation
    $tagList  = '';

    // get the tags
    $tagOldList = get_post_custom_values('ttaglist'); // compatibility with tags from TechnoTag by Keith McDuffee <http://www.gudlyf.com>
    $tagNewList = get_post_custom_values('technorati');

    $tagList = $tagOldList[0] . ',' . $tagNewList[0];

    $tagArray = preg_split("/[,]+/", $tagList, -1, PREG_SPLIT_NO_EMPTY);
    sort($tagArray);

    if (count($tagArray) == 0)
    {
        // definetely no tags
    }
    else
    {
        $tagFormattedArray = array();

        foreach ($tagArray as $tag)
        {
            $tag = trim(strtolower($tag));

            if ($tag == '')
                continue;

            $tagFixed = bon_technorati_fixTag($tag);

            $tagFormattedArray[] = str_replace("%URL%", $bon_TechnoTaggingOptions['technoTag_link'] . $tagFixed, str_replace("%TAG%", $tag, $bon_TechnoTaggingOptions['technoTag_format']));
        }

        if (count($tagFormattedArray) > 0)
        {
            // check if there were no tags (spaces can confuse previous check)
            $text .= $bon_TechnoTaggingOptions['technoTag_prefix'];
            $text .= implode($bon_TechnoTaggingOptions['technoTag_seperator'], $tagFormattedArray);
            $text .= $bon_TechnoTaggingOptions['technoTag_suffix'];
        }
    }

    return $text;
} // technoratiTagging

function bon_technorati_fixTag($text)
{
    return str_replace(" ", "+", $text);
} // technorati_fixTag

function bon_technoratiTaggingRewriteRules($rules)
{
    $bon_TechnoTaggingOptions = bon_technoratiTaggingLoadOptions();

    // let's add our rewrite rule to the system
    $rules[$bon_TechnoTaggingOptions['technoTag_rewrite_catch']] = 'index.php?plugin=technoratitags&tag=$1';

    return $rules;
} // technoratiTaggingRewriteRules

function bon_technoratiTaggingPageDisplay()
{
    global $wpdb;

    $bon_TechnoTaggingOptions = bon_technoratiTaggingLoadOptions();

    if (isset($_REQUEST['plugin']) && $_REQUEST['plugin'] == 'technoratitags' && isset($_REQUEST['tag']))
    {
        $bon_TechnoTaggingOptions['technoTag_posts_in_archive'] = (int)$bon_TechnoTaggingOptions['technoTag_posts_in_archive'];
        query_posts('showposts=' . $bon_TechnoTaggingOptions['technoTag_posts_in_archive']);

        include(TEMPLATEPATH . '/archive.php');
        exit;
    }
} // technoratiTaggingPageDisplay

function bon_technoratiTaggingPostsWhere($where)
{
    global $wpdb;

    if (isset($_REQUEST['plugin']) && $_REQUEST['plugin'] == 'technoratitags' && isset($_REQUEST['tag']))
    {
        // if we are displaying our page
        $tag = str_replace("+", " ", trim($_REQUEST['tag']));
        $where .= " AND `meta_value` LIKE '%" . $wpdb->escape($tag) . "%'";
    }

    return $where;
} // bon_technoraiTaggingPostsWhere

function bon_technoratiTaggingPostsJoin($join)
{
    global $wpdb;

    if (isset($_REQUEST['plugin']) && $_REQUEST['plugin'] == 'technoratitags' && isset($_REQUEST['tag']))
    {
        // if we are displaying our page
        $tag = str_replace("+", " ", trim($_REQUEST['tag']));
        $join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id=$wpdb->posts.ID";
    }

    return $join;
} // bon_technoratiTaggingPostsJoin

function bon_technoratiTaggingAdminMenu()
{
    $pagefile = basename(__FILE__);
    add_options_page('Technorati Tagging Options Page', 'Technorati Tagging', 8, $pagefile);
}

add_action('admin_menu', 'bon_technoratiTaggingAdminMenu');

} // if(is_plugin_page())

add_filter('the_content', 'bon_technoratiTagging');
add_filter('rewrite_rules_array', 'bon_technoratiTaggingRewriteRules');
add_filter('template_redirect', 'bon_technoratiTaggingPageDisplay');
add_filter('posts_where', 'bon_technoratiTaggingPostsWhere');
add_filter('posts_join', 'bon_technoratiTaggingPostsJoin');

?>