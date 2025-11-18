$(".orderSaved a").on("click", function(e){
  e.preventDefault();
  //remove all list-group-item active class
  $(".orderSaved .list-group-item").get().forEach(element => {
    element.classList.remove("active");
  });
  //Active the current item
  $(e.target).parent().get()[0].classList.add("active");
});

    //Normal button action up and down

$("body").on("click", ".quantityBox span button", function (e) {
  e.preventDefault();
  let buttonClass = e.target.classList;
  let input = e.target.parentElement.parentElement.querySelector("input");
  
  if (buttonClass.contains("remove") && input.value > 0){
      input.focus();
      input.value--;
  } else if (buttonClass.contains("add") && input.value >= 0){
      input.focus();
      input.value++;
  } else {
    input.focus();
  }

});

      //Modification of quantity after php adding
$("body").on("click", ".orderList .quantityBox span button", function (e) {
  e.preventDefault();
  let buttonClass = e.target.classList;
  let input = e.target.parentElement.parentElement.querySelector("input");
  const id = parseInt($(input).attr("id").substr(5));
  
  if (buttonClass.contains("remove") && input.value > 0){
    quantityUpdate(id, input);
  } else if (buttonClass.contains("add") && input.value >= 0){
    quantityUpdate(id, input);
  }
});


//Enter touch option to modificate the php creation
$("body").on("keyup", ".orderList .quantityBox input", function (e) {
  e.preventDefault();
  if (e.keyCode == 13){
    let input = e.target;
    const id = parseInt($(e.target).attr("id").substr(5));
    quantityUpdate(id, input);
  }
});

//Modificate the quantity
function quantityUpdate(id, inputValue){
  $.post("customerorder/quantityupdate",
    {
      "id": id,
      "quantity": inputValue.value
    },
    function (data, textStatus, jqXHR) {
      $($("#item"+data["id"]+" .orderValue")).text(data["montant"]);
      alerter(data);
    },
    "json"
  );
}

//Delete option button
$(".orderList").on("click", "tr[id^='item'] .close-btn button", function(e){
  const parentId = parseInt($(e.target).parents("tr[id^='item']").attr('id').substr(4));
  $.post("customerorder/deleteproduct",
    {
      "id": parentId,
    },
    function (data, textStatus, jqXHR) {
      $(".orderList #item"+data["id"]).remove();
      alerter(data);
    },
    "json"
  );
});