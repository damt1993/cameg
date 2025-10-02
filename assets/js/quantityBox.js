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
