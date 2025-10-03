    //Normal button action up and down

$(".quantityBox span button").on("click", function (e) {
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

    //Dynamic object created for action up and down
$(document).on("click", ".newQuantityBox span button", function (e) {
  e.preventDefault();
  let buttonClass = e.target.classList;
  let input = e.target.parentElement.parentElement.querySelector("input");
  const id = parseInt($(input).attr("id").substr(5));
  
  if (buttonClass.contains("remove") && input.value > 0){
      input.focus();
      input.value--;
      quantityUpdate(id, input);
  } else if (buttonClass.contains("add") && input.value >= 0){
      input.focus();
      input.value++;
      quantityUpdate(id, input);
  } else {
    input.focus();
  }
});


      //Modification of quantity after php adding
$(".orderList .quantityBox span button").on("click", function (e) {
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
$(".orderList .quantityBox input").on("keyup", function (e) {
  e.preventDefault();
  if (e.keyCode == 13){
    let input = e.target;
    const id = parseInt($(e.target).attr("id").substr(5));
    quantityUpdate(id, input);
  }
});

//Enter touch to modificate the dynamic js creation
$(document).on("keyup", ".newQuantityBox input", function (e) {
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
    },
    "json"
  );
}
