(function($){

  function initializeChosen(){

    var chosen_elements = [];

    for(var i=1;i<=100;i++){

      if( $('#language' + i).length && $('#locale' + i).length ){

        chosen_elements.push('#language' + i);
        chosen_elements.push('#locale' + i);

      }

    }

    $(chosen_elements.join(',')).chosen();

  }

  $(document).ready(function(){

    initializeChosen();

  });

})(jQuery);