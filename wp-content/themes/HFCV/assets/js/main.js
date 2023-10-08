/*
 *
 * HFCV Main Js Functions
 *
 */

// init theme style variables
document.addEventListener("DOMContentLoaded", function () {
  updateStyleVariables("hfcv-style-css", initData.style_variables);
});

// typing effect
document.addEventListener("DOMContentLoaded", function () {
  var inputs = document.getElementsByClassName("typing");
  for (let input of inputs) {
    var text = input.innerHTML;
    var lines = text.split("<br>");
    input.innerHTML = "";
    input.style.display = "block";
    var i = 0;
    var innerSpan = document.createElement("span");
    var timer = setInterval(function () {
      innerSpan.innerHTML = lines[i].trim();
      innerSpan.style.width = lines[i].length + 2 + "ch";
      innerSpan.classList.add("typing-line");
      input.appendChild(innerSpan);
      i++;
      if (i == lines.length) i = 0;
    }, 3000);
  }
});

// update style variables
const updateStyleVariables = function (id, data) {
  const stylesheet = document.getElementById(id).sheet;
  // Loop through the rules to find the :root rule where the variable is defined
  for (const rule of stylesheet.cssRules) {
    if (rule instanceof CSSStyleRule && rule.selectorText === ":root") {
      // Update the value of the CSS variable
      for (let key in data) {
        rule.style.setProperty(key, data[key]);
      }
    }
  }
};

(function ($) {
  $(document).ready(function ($) {
    //svg realtime color
    var stylesheet = document.getElementById("hfcv-style-css");
    if (stylesheet) {
      var primaryColor = getComputedStyle(stylesheet).getPropertyValue(
        "--hfcv-color-primary-color"
      );
    }
    $("object").on("load", function () {
      var svgDoc = $(this)[0].contentDocument;

      if (svgDoc) {
        $(svgDoc).find("svg *").css("fill", primaryColor);
      }
    });

    //owl
    $(".owl-carousel").each(function (index, element) {
      var columns = $(element).data("columns");
      $(element).owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
          0: {
            items: columns > 1 ? 2 : 1,
            nav: true,
          },
          900: {
            items: columns,
            nav: true,
          },
        },
      });
    });

    //Mobile Menu

    $("#mobile-menu-drop").change(function () {
      var selectedUrl = $(this).val();
      if (selectedUrl) {
        window.location.href = selectedUrl;
      }
    });

    //ajax forms submittion
    $('form[action="ajax"]')
      .find(".btn-submit")
      .on("click", function (e) {
        e.preventDefault();
        var form = $(this).closest("form");
        var formData = form.serialize();
        var formMethod = form.attr("method");
        var responceBox = form.find(".responce");
        $.ajax({
          type: formMethod,
          url: ajax_object.ajax_url,
          data: formData,
          success: function (response) {
            response = JSON.parse(response);
            if (response.status == "success") {
              responceBox.html(response.content);
              responceBox.show();
            } else {
              content = response.content + "<ul>";
              response.invalids.forEach((element) => {
                let labelElement = document.querySelector(
                  "label[for='" + element + "']"
                );
                content += "<li>" + labelElement.innerText + "</li>";
              });
              content += "</ul>";
              responceBox.html(content);
              responceBox.show();
            }
          },
        });
      });

    //Copy / Share
    $(".share-button").on("click", function (e) {
      e.preventDefault();
      const textField = $(this).closest("section").find(".shortened-url");
      const url = textField.val();

      console.log(url);
      textField.select();
      document.execCommand("copy");

      // Check if the browser supports the Web Share API
      if (navigator.share) {
        const shareData = {
          url: url,
        };
        navigator.share(shareData);
      }
    });
  });
})(jQuery);
