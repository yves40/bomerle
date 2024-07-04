//  -------------------------
//  categories.js
//  -------------------------

$(document).ready(function () {

  $('#rotator').on('click', (event) => {
    let img = $(event.target).parent().parent().find('.imagesmall');
    let payload = {
      categoryid: $(img).attr('id')
    }

    $.ajax({
      type: "POST",
      url: `/bootadmin/category/rotatephoto`,
      dataType: "json",
      data: JSON.stringify(payload),
      async: false,
      success: function (response) {
          console.log(response);
          $(img).css('transform', `rotate(${response.rotation}deg)`);
      },
      error: function (xhr) {
          console.log(xhr);
      }
    });    
  })
})
