jQuery(document).ready(function ($) {
  var mediaUploader;

  $("body").on("click", "#taxonomy-image-button", function (e) {
    e.preventDefault();

    if (mediaUploader) {
      mediaUploader.open();
      return;
    }

    mediaUploader = wp.media({
      title: "Choose Image",
      button: {
        text: "Choose Image",
      },
      multiple: false,
    });

    mediaUploader.on("select", function () {
      var attachment = mediaUploader.state().get("selection").first().toJSON();
      $(".custom-media-url").val(attachment.url);
      $("#taxonomy-image-container").html(
        '<img src="' + attachment.url + '" style="max-width:100px;">'
      );
    });

    mediaUploader.open();
  });
});

jQuery(document).ready(function ($) {
  // Uploading files using WordPress media library.
  let file_frame;
  $(document).on("click", ".custom-image-button", function (e) {
    e.preventDefault();
    let $button = $(e.target);
    let $menuItem = $(e.target).closest(".menu-item");
    let $field = $menuItem.find(".custom-image-input");
    let $preview = $menuItem.find(".custom-image-preview");

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: $button.data("uploader_title"),
      button: {
        text: $button.data("uploader_button_text"),
      },
      multiple: false,
    });

    // When an image is selected, run a callback.
    file_frame.on("select", function () {
      let attachment = file_frame.state().get("selection").first().toJSON();
      $field.val(attachment.url);
      $preview.attr("src", attachment.url);
    });

    // Finally, open the modal.
    file_frame.open();
  });

  // Removing the image.
  $(document).on("click", ".custom-image-remove-button", function (e) {
    e.preventDefault();
    let $menuItem = $(e.target).closest(".menu-item");
    let $field = $menuItem.find(".custom-image-input");
    let $preview = $menuItem.find(".custom-image-preview");

    $field.val("");
    $preview.attr("src", "");
  });
});

jQuery(document).ready(function ($) {
  var galleryUploader;

  $("#upload_gallery_button").click(function (e) {
    e.preventDefault();
    var galleryInputName = e.target.getAttribute("data-name");

    if (galleryUploader) {
      galleryUploader.open();
      return;
    }

    galleryUploader = wp.media({
      title: "Upload Gallery Images",
      button: {
        text: "Add to gallery",
      },
      library: {
        type: "image",
      },
      multiple: true,
    });

    galleryUploader.on("select", function () {
      var selection = galleryUploader.state().get("selection");

      selection.map(function (attachment) {
        attachment = attachment.toJSON();
        var imageHtml =
          '<div class="gallery_image">' +
          '<img src="' +
          attachment.url +
          '" alt="" width="100" height="100" />' +
          '<input type="hidden" name="' +
          galleryInputName +
          '[]" value="' +
          attachment.id +
          '" />' +
          '<button class="remove_gallery_image">X</button>' +
          "</div>";

        $("#hfcv_gallery_images_preview").append(imageHtml);
      });
    });

    galleryUploader.open();
  });

  $(document).on("click", ".remove_gallery_image", function () {
    imagesCount = $("#gallery_images_preview").children().length;
    $(this).parent(".gallery_image").remove();
  });
});
