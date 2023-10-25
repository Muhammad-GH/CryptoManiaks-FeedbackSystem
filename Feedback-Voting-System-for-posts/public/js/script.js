jQuery(function ($) {
  $(".feedback-system").on(
    "click",
    ".feedback-system-btn:not(.disabled):not(.active)",
    function (e) {
      e.preventDefault();
      const feedback_action = $(this).attr("data-action");
      const { ajax_url: url, nonce, post_id } = feedback_system;

      const yesBtn = $(`.feedback-system-btn[data-action="yes"]`);
      const noBtn = $(`.feedback-system-btn[data-action="no"]`);
      const p_tag = $(this).closest(".feedback-system").find("p");

      yesBtn.prop("disabled", true);
      noBtn.prop("disabled", true);

      $.ajax({
        type: "POST",
        dataType: "json",
        url,
        data: {
          action: "feedback_action_post",
          feedback_action,
          nonce,
          post_id,
        },
        success: function (response) {
          if (response.success) {
            const { yes, no } = response.votes;

            let yes_txt = "Yes";
            let no_txt = "No";
            let p_txt = "Was This Article Helpful?";

            if (yes || no) {
              yes_txt = `${yes} %`;
              no_txt = `${no} %`;
            }

            if (response.is_user_voted) {
              p_txt = "Thank You For Your Feedback.";
            }

            p_tag.html(p_txt);

            yesBtn.find("span").html(yes_txt);
            noBtn.find("span").html(no_txt);

            yesBtn.prop("disabled", false);
            noBtn.prop("disabled", false);

            yesBtn.addClass(feedback_action === "yes" ? "active" : "disabled");
            noBtn.addClass(feedback_action === "no" ? "active" : "disabled");
          }
        },
      });
    }
  );
});
