// jQuery(document).ready(function ($) {
//   var offset = 0; // Initialize the offset

//   $(".load-more-button").click(function (e) {
//     e.preventDefault();
//     offset += 10; // Increment the offset by 10

//     $.ajax({
//       url: filter_ajax_object.ajax_url,
//       type: "POST",
//       data: {
//         action: "filter_products",
//         brands: filter_brands, // Pass the selected brands
//         sortby: filter_sortby, // Pass the selected sortby option
//         offset: offset, // Pass the offset
//       },
//       success: function (response) {
//         $(".woocommerce").append(response); // Append the new products
//       },
//     });
//   });
// });

var selectedBrands = [];
var sortBy = "menu_order";
var offset = 0; // Initialize the offset

jQuery(document).ready(function ($) {
  $(".products").after(
    '<button id="load-more-button" class="load-more-button">Load More</button>'
  );

  $(".filter-checkbox, #sort-by").change(function () {
    selectedBrands = [];
    $(".filter-checkbox:checked").each(function () {
      selectedBrands.push($(this).val());
    });
    sortBy = $("#sort-by").val();
    offset = 0;
    filterProducts();
  });

  $("#load-more-button").click(function (e) {
    e.preventDefault();
    console.log("called");
    offset += 3;
    filterProducts();
  });

  function filterProducts() {
    console.log("func");
    $.ajax({
      url: filter_ajax_object.ajax_url,
      type: "POST",
      data: {
        action: "filter_products",
        brands: selectedBrands,
        sort_by: sortBy,
        offset: offset,
      },
      success: function (response) {
        if (offset === 0) {
          $(".products").html(response);
        } else {
          $(".products").append(response);
        }
        // Reattach change event to #sort-by element
        $("#sort-by")
          .off("change")
          .on("change", function () {
            sortBy = $(this).val();
            offset = 0;
            filterProducts();
          });
      },
    });
  }
});
