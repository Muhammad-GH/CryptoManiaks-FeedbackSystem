<?php 
$votes = $this->get_vote_percentages();
$post_id = get_the_ID();

$yes_votes = $votes["yes"];
$no_votes = $votes["no"];

$user_token = $this->user_obj->get_user_token();
$is_user_voted = $this->is_user_voted($post_id, $user_token);

$yes_txt = "Yes";
$no_txt = "No";
$p_txt = "Was This Article Helpful?";

if($yes_votes || $no_votes) {
    $yes_txt = "$yes_votes %";
    $no_txt = "$no_votes %";
}

if($is_user_voted) {
    $p_txt = "Thank You For Your Feedback.";
}

$yes_class = $no_class = "";

// Check if User Voted and change class accordingly
if( isset($_SESSION["vote_$post_id"]) )
{
    $yes_class = $_SESSION["vote_$post_id"] === "yes" ? "active" : "disabled";
    $no_class = $_SESSION["vote_$post_id"] === "no" ? "active" : "disabled";
}
?>

<div class="feedback-system">
    <p><?php echo $p_txt; ?></p>
    <div class="feedback-system-btns">
        <button type="button" class="feedback-system-btn <?php echo $yes_class; ?>" data-action="yes">
            <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 512"><path d="M189.759 8.784c136.533-36.583 276.872 44.442 313.456 180.974 36.585 136.534-44.441 276.873-180.974 313.457S45.369 458.773 8.785 322.241C-27.8 185.708 53.226 45.368 189.759 8.784zM119.191 295.6c69.524 90.131 212.547 88.174 274.078-.172l10.027 9.973c-68.927 116.97-214.851 121.104-294.594.63l10.489-10.431zm71.566-161.137c17.913 0 32.433 21.028 32.433 46.965 0 25.939-14.52 46.965-32.433 46.965s-32.434-21.026-32.434-46.965c0-25.937 14.521-46.965 32.434-46.965zm130.487 0c17.912 0 32.433 21.028 32.433 46.965 0 25.939-14.521 46.965-32.433 46.965-17.913 0-32.434-21.026-32.434-46.965 0-25.937 14.521-46.965 32.434-46.965z"/></svg> 
            <span><?php echo $yes_txt; ?></span>
        </button>

        <button type="button" class="feedback-system-btn <?php echo $no_class; ?>" data-action="no">
            <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 511.998"><path d="M189.757 8.786c136.535-36.587 276.875 44.44 313.459 180.972C539.799 326.29 458.775 466.63 322.24 503.213 185.708 539.797 45.368 458.772 8.784 322.241-27.799 185.706 53.225 45.369 189.757 8.786zm3.012 125.676c17.914 0 32.433 21.029 32.433 46.965 0 25.938-14.519 46.965-32.433 46.965s-32.433-21.027-32.433-46.965c0-25.936 14.519-46.965 32.433-46.965zm-35.967 225.586a166.947 166.947 0 00-36.868 35.11l-.083.093a.932.932 0 01-1.316.003l-10.456-10.398a.94.94 0 01-.156-1.211c19.354-29.24 42.609-51.181 67.715-65.977 27.387-16.137 56.989-23.778 86.155-23.134 29.175.64 57.923 9.571 83.596 26.565 22.51 14.902 42.672 36.017 58.688 63.184a.932.932 0 01-.123 1.169l-10.152 10.081a.936.936 0 01-1.3-.233c-8.866-12.733-19.439-23.663-31.234-32.758-28.496-21.98-64.195-33.344-100.468-33.845-36.286-.499-73.159 9.872-103.998 31.351zm166.454-225.586c17.913 0 32.433 21.029 32.433 46.965 0 25.938-14.52 46.965-32.433 46.965-17.914 0-32.433-21.027-32.433-46.965 0-25.936 14.519-46.965 32.433-46.965z"/></svg> 
            <span><?php echo $no_txt; ?></span>
        </button>
    </div>
</div>