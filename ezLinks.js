jQuery(function ($) {

  var popup = false;
  var linkId, productId, tableId;

  $("#closePopup").click(function () {
    hidePopup();
  });

  $("#overlayEffect").click(function () {
    hidePopup();
  });

  $('body').bind('click', function () {
    $("#toolbar,#prodToolbar,#linkToolbar").hide();
  });

  function center(elem) {
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $("#popupContainer").height();
    var popupWidth = $("#popupContainer").width();
    $(elem).css({
      "position": "absolute",
      "top": windowHeight / 2 - popupHeight / 2,
      "left": windowWidth / 2 - popupWidth / 2
    });
  }

  function showPopup(response) {
    if (popup === false) {
      $("#ezPopupResponse").html(response);
      $("#ezPopupResponse input:disabled, #ezPopupResponse textarea:disabled").fadeTo(1000, 0.5);
      $("#overlayEffect").fadeIn("slow");
      $("#popupContainer").fadeIn("slow");
      $("#closePopup").fadeIn("slow");
      center("#popupContainer");
      popup = true;
    }
  }

  function hidePopup() {
    if (popup === true) {
      $("#overlayEffect").fadeOut("slow");
      $("#popupContainer").fadeOut("slow");
      $("#closePopup").fadeOut("slow");
      popup = false;
    }
  }

  $(".toolBar").live("click", function () {
    $(this).parent().hide();
    a = $(this).attr('id');
    if (a !== "quit")
      action(a);
  });

  function action(a) {
    var confirm = a;
    var action = a.split('_')[0];
    if (action === confirm || action + '_prod' === confirm)
      $("#ezPopupHeader").html("Confirm " + action.charAt(0).toUpperCase() + action.slice(1));
    else
      $("#ezPopupHeader").html("Done!");
    var formName = "#" + action + "_form :input";
    var formData = "";
    $(formName).each(function (i, e) {
      formData += "&" + e.id + "=" + encodeURIComponent(e.value);
    });
    $.post(EzlAjax.ajaxurl,
            {action: action,
              confirm: confirm,
              linkId: linkId,
              productId: productId,
              data: formData,
              nonce: EzlAjax.nonce},
    function (response) {
      var newRow, modifiedRow;
      var splitResponse = response.split(':new:');
      if (response === splitResponse[0]) { // not a new row
        splitResponse = response.split(':modified:');
        modifiedRow = splitResponse[1];
        rowId = splitResponse[2];
      }
      else {
        newRow = splitResponse[1];
      }
      response = splitResponse[0];
      if (newRow) {
        $("#" + tableId + " tr:last").after(newRow).css('background', 'gold');
      }
      if (modifiedRow) {
        if (tableId) {
          var oldClass = $(rowId).attr('class');
          $(rowId).replaceWith(modifiedRow);
          $(rowId).attr('class', oldClass);
        }
        else {
          $(rowId).css("text-decoration", "line-through");
        }
      }
      popup = false;
      showPopup(response);
      popup = true;
    }
    );
  }

  $(".ezlink").hover(function () {
    var pos = $(this).position();
    var height = $(this).outerHeight();
    linkId = $(this).attr('id');
    productId = '';
    tableId = '';
    $("#linkToolbar").css({
      position: "absolute",
      top: (pos.top - height) + "px",
      left: (pos.left) + "px",
      height: "18px"
    }).show();
  });

  $(".adminProdTable, .adminLinkTable").live('hover', function () {
    var pos = $(this).position();
    var height = $(this).outerHeight();
    tableId = $(this).closest('table').attr('id');
    var toolBarCls = "#prodToolbar";
    if (tableId === "adminProdTable") {
      toolBarCls = "#prodToolbar";
      productId = $(this).attr('id');
      linkId = '';
    }
    if (tableId === "adminLinkTable") {
      toolBarCls = "#linkToolbar";
      productId = '';
      linkId = $(this).attr('id');
    }
    $(toolBarCls).css({
      position: "absolute",
      top: (pos.top + height - 20) + "px",
      left: (pos.left + 10) + "px"
    }).show();
  });

  $(".adminProdTable").live('mouseleave', function () {
    $("#prodToolbar").hide();
  });

  $(".adminLinkTable").live('mouseleave', function () {
    $("#linkToolbar").hide();
  });

  $(".toolBar").live("hover", function () {
    $(this).parent().show();
  });

  $(document).on('keydown', function (e) {
    if (e.keyCode === 27) { // ESC
      hidePopup();
    }
  });

//  $(document).ready(function() {
//    $("#ezlWrap").hide();
//    $("#loading").show();
//  });

  window.onload = function () {
    $("#ezlWrap").fadeIn("slow");
    $("#loading").fadeOut("slow");
  };

  $("#resetOptions").click(function () {
    if (window.prompt("This will REALLY discard all your link packages and link sales. No undo!\n\
Are you sure? \n\
Type in YES (all capitals) to confirm.") === "YES")
      return true;
    else
      return false;
  });

}, jQuery);
