<!--custom code to show parent categories in one dropdown and sub-categories in another dropdown based on parent category choosen-->
<div class="dokan-form-group parent-cat-div">

<?php
  $selected_cat_custom  = dokan_posted_input( 'product_cat_custom' );
  $category_args_custom =  array(
    'show_option_none' => __( '- Select item category -', 'dokan-lite' ),
    'hierarchical'     => 1,
    'hide_empty'       => 1,
    'name'             => 'product_cat_custom',
    'id'               => 'product_cat_custom',
    'taxonomy'         => 'product_cat',
    'title_li'         => '',
    'class'            => 'product_cat dokan-form-control dokan-select2',
    'exclude'          => '',
    'parent'           => 0,
    'selected'         => $selected_cat_custom,
  );

  wp_dropdown_categories( apply_filters( 'dokan_product_cat_dropdown_args', $category_args_custom ) );
?>

<script type="text/javascript">

  $(document).ready(function() {

    // Onchange of parent category dropdown
    $("select#product_cat_custom").on('change',function(){

      //Save product title,price,discount price,short-description,category into session storage  
      var itemName = $('#post-title').val();
      sessionStorage.setItem("itemName", itemName);

      var itemPrice = $("input[name=_regular_price]").val();
      sessionStorage.setItem("itemPrice", itemPrice);

      var itemDiscountPrice = $("input[name=_sale_price]").val();
      sessionStorage.setItem("itemDiscountPrice", itemDiscountPrice);

      var itemShortDesc = $('#post-excerpt').val();
      sessionStorage.setItem("itemShortDesc", itemShortDesc);

      var selectedCat = $('#product_cat_custom option:selected').val();
      sessionStorage.setItem("selectedCat", selectedCat);

      var selectedCatName = $('#product_cat_custom option:selected').text();
      sessionStorage.setItem("selectedCatName", selectedCatName);

      //Reload page to pass category id as a parameter into url
      window.location = window.location.pathname + '?catid=' + selectedCat;
        
    });

    //Show and hide sub-categories dropdown based on query string of url
    if (window.location.href.indexOf('?catid=') > 0) {
      $(".show-custom").show();
    } else {
      $(".show-custom").hide();
    }

    //Clear session storage after 60 sec
    setTimeout(function() { sessionStorage.clear(); }, (60000));

  });

window.onload = function() {
   //retrieve all stored values on load
    var getSelectedCat = sessionStorage.getItem("selectedCat");  
    $("select#product_cat_custom option[value='"+getSelectedCat+"']").prop("selected",true);

    //Showing category name as title of dropdown(unless category dropdown still shows text to choose category)
    var getSelectedCatName = sessionStorage.getItem("selectedCatName");  
    if(getSelectedCatName != null) {
      $(".parent-cat-div #select2-product_cat_custom-container").prop("title",getSelectedCatName);
      $(".parent-cat-div #select2-product_cat_custom-container").text(getSelectedCatName);
    }

    var getitemName = sessionStorage.getItem("itemName");
    $('#post-title').val(getitemName);

    var getitemPrice = sessionStorage.getItem("itemPrice");
    $("input[name=_regular_price]").val(getitemPrice);

    var getitemDiscountPrice = sessionStorage.getItem("itemDiscountPrice");
    $("input[name=_sale_price]").val(getitemDiscountPrice);

    var getitemShortDesc =  sessionStorage.getItem("itemShortDesc");
    $('#post-excerpt').val(getitemShortDesc);
}

</script>
</div>
  <!--custom code end-->


<div class="dokan-form-group show-custom">
    <?php

    include_once DOKAN_LIB_DIR.'/class.taxonomy-walker.php';

    $selected_cat  = dokan_posted_input( 'product_cat', true );
    $selected_cat  = empty( $selected_cat ) ? array() : $selected_cat;

    //get category id from url
    $cat_id = $_GET['catid'];

    $drop_down_category = wp_dropdown_categories( apply_filters( 'dokan_product_cat_dropdown_args', array(
        'show_option_none' => __( '', 'dokan-lite' ),
        'hierarchical'     => 1,
        'hide_empty'       => 0,
        'name'             => 'product_cat[]',
        'id'               => 'product_cat',
        'taxonomy'         => 'product_cat',
        'title_li'         => '',
        'class'            => 'product_cat dokan-form-control dokan-select2',
        'exclude'          => '',
        'selected'         => $selected_cat,
        'echo'             => 0,
        'parent'           => $cat_id, //parent category id from url
        'walker'           => new DokanTaxonomyWalker()
    ) ) );

    echo str_replace( '<select', '<select data-placeholder="'.__( 'Select product sub-category', 'dokan-lite' ).'" multiple="multiple" ', $drop_down_category );
    ?>
</div>