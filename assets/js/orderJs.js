$(".orderSaved a").on("click", function(e){
  e.preventDefault();
  //remove all list-group-item active class
  $(".orderSaved .list-group-item").get().forEach(element => {
    element.classList.remove("active");
  });
  //Active the current item
  $(e.target).parent().get()[0].classList.add("active");
});