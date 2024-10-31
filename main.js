var ratedIndex = -1;

function resetColors() {
    $('.rps i').css("color", "#e2e2e2"); // Ajout du symbole # pour la couleur
}

function setStars(max) {
    for (var i = 0; i <= max; i++) {
        $(".rps i:eq(" + i + ")").css("color", "#f7bf17"); // Ajout du symbole # pour la couleur
    }
}

$(document).ready(function () {
    $(".rpc i, .review-bg").click(function () {
        $(".review-modal").fadeOut();
    });
    
    $(".opmd").click(function () {
        $(".review-modal").fadeIn();
    });

    resetColors();

    $(".rps i").mouseover(function () {
        resetColors();
        var currentIndex = parseInt($(this).data("index"));
        setStars(currentIndex);
    });

    $(".rps i").on("click", function () {
        ratedIndex = parseInt($(this).data("index"));
        localStorage.setItem("rating", ratedIndex);
        $(".starRateV").val(parseInt(localStorage.getItem("rating")));
    });

    $(".rps i").mouseleave(function () {
        resetColors();
        if (ratedIndex != -1) {
            setStars(ratedIndex);
        }
    });

    if (localStorage.getItem("rating") != null) {
        setStars(parseInt(localStorage.getItem("rating")));
        $(".starRateV").val(parseInt(localStorage.getItem("rating")));
    }

    $(".rpbtn").click(function () {
        if ($("#rate-field").val() == "") {
            $(".rate-error").html("Please fill in the box!");
        }
        else if ($(".rateName").val() == "") {
            $(".rate-error").html("Please enter your name!"); // Correction de htlm en html
        }
        else if (localStorage.getItem("rating") == null) {
            $(".rate-error").html("Please select a star to rate!"); // Correction de htlm en html
        }
        else {
            $(".rate-error").html("");

            var $form = $(this).closest(".rmp");
            var starRate = $form.find(".starRateV").val(); // Correction de starRatev en starRateV
            var rateMsg = $form.find(".rateMsg").val();
            var date = $form.find(".rateDate").val();
            var name = $form.find(".rateName").val();

            $.ajax({
                url: "rate.php",
                type: "POST",
                data: {
                    starRate: starRate,
                    rateMsg: rateMsg,
                    date: date,
                    name: name,
                },
                success: function (data) {
                    window.location.reload();
                }
            });
        }
    });
});
