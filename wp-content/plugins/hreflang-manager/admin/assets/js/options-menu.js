(function($){

  function initializeChosen(){

    var chosen_elements = [];

    for(var i=1;i<=100;i++){

      if( $('#da-hm-default-language-' + i).length && $('#da-hm-default-locale-' + i).length ){

        chosen_elements.push('#da-hm-default-language-' + i);
        chosen_elements.push('#da-hm-default-locale-' + i);

      }

    }

    chosen_elements.push('#da-hm-show-log');
    chosen_elements.push('#da-hm-https');
    chosen_elements.push('#da-hm-detect-url-mode');
    chosen_elements.push('#da-hm-auto-delete');
    chosen_elements.push('#da-hm-import-language');
    chosen_elements.push('#da-hm-import-locale');
    chosen_elements.push('#da-hm-sanitize-url');
    chosen_elements.push('#da-hm-sample-future-permalink');

    $(chosen_elements.join(',')).chosen();

  }

  $(document).ready(function(){

    initializeChosen();

  });

})(jQuery);