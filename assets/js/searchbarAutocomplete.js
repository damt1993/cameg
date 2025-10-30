const jsProduct = JSON.parse($(".data-list").attr("data-list"));

function reacter(dataSubmit, parentData){
  const isParent = $(parentData).parents()[2];

  //Order manager
  if (isParent.classList.contains("orderCreator")){
    $(".orderChoice .product span").text(dataSubmit.name);
    $(".orderChoice .price span").text(dataSubmit.price+" F Cfa");
    $(".orderChoice .publicPrice span").text(dataSubmit.publicPrice+" F Cfa");
    $(".orderChoice .peromptAt span").text(dataSubmit.peromptAt);
    $(".orderCreator .searchbarAutocomplete input").val("");
    $(".orderChoice .quantityBox input").val(0).focus().select();
    addOrderItem(dataSubmit);
  } else {
    console.log("Nosé passa");
  }
}

//Reinit order item information autocomplete
function reinitOrderItemData() {
  $(".orderChoice .product span").text("");
  $(".orderChoice .price span").text("");
  $(".orderChoice .publicPrice span").text("");
  $(".orderChoice .peromptAt span").text("");
  $(".orderCreator .searchbarAutocomplete input").focus();
  $(".orderChoice .quantityBox input").val(0);
}

//Set the autofocus on
$(".orderCreator .searchbarAutocomplete input").focus();

//Initialise the searchbarAutocomplete
searchbarAutocomplete($(".orderCreator .searchbarAutocomplete input"), jsProduct);


//Adding order item function
function addOrderItem(dataSubmit){
  //Add new order item in the order list
  $(".orderChoice form").one("submit", function (e) { 
    e.preventDefault();
    const submitter = e.target;
    const quantitySubmit = parseInt(submitter.querySelector("input").value)
    if (quantitySubmit>0){
      dataSubmit.quantity = quantitySubmit;
      
      $.post("customerorder/update", dataSubmit,
        function (data, textStatus, jqXHR) {
          const dataReturn = data["data"];
          if (dataReturn["id"]){
            //Update the quantity
            $("#input"+dataReturn["id"]).val(dataReturn["newQuantity"]);
          } else {
            //add the new html item
            $(".orderList").prepend(dataReturn);
          }
        },
        "json",
      );
      reinitOrderItemData();
    }
  });

}
//Autocomplete function
function searchbarAutocomplete(searchbarInputer, dataToAutocomplete){
  //index of the focuser
  let currentItem = 0;

  //event to react after all key press
  searchbarInputer.on("input", function(){
    //reinit reverser value
    reverser = 0;
    //Delete the list of matching items
    deleteListDiv();
    currentItem = 0;

    //get the input value
    let inputValue =this.value;
    if (inputValue){
      //Find the element who is match with our dataToAutocomplete
      let matchingData = dataToAutocomplete.filter(element=>element.name.toLowerCase().includes(inputValue.toLowerCase()));
      if (matchingData.length<1){
        return;
      }

      //Create the container for the result   
      let listDiv = document.createElement("div");
      listDiv.classList.add(this.id+"Container");

      //Attache this div to the parentNode of the input
      this.parentNode.appendChild(listDiv);

      //Put the matching items in the listField
      matchingData.forEach(element => {
        const matchingItem = document.createElement("div");
        matchingItem.setAttribute("id", "matchingItem"+element.id);
        matchingItem.innerHTML = element.name;
        //Event to appy when the user click on the matching item
        $(matchingItem).on("click", function(e){
          $(searchbarInputer).val(e.target.innerText);
          const currentThis = this;
          reacter(element, currentThis);
          deleteListDiv();
        });
        listDiv.appendChild(matchingItem);
      });
      currentItemSelect(currentItem);
    }
  });

  let scroller = 0;
  let reverser = 0;

  searchbarInputer.on("keyup", function(e){
    let listLength = $("."+searchbarInputer.attr("id")+"Container div").length;
    if (e.keyCode==40){
      reverser ++;
      currentItem ++;
      if (currentItem == listLength){
        currentItem --;
      }
      currentItemSelect(currentItem);
      reverserFunction(listLength);
    } else if (e.keyCode == 38){
      currentItem --;
      reverser --;
      if (currentItem < 0){
        currentItem ++;
      }
      currentItemSelect(currentItem);
      reverserFunction();
    }
    
    if(e.keyCode == 13){
      const itemValid = $("."+searchbarInputer.attr("id")+"Container div")[currentItem];
      if (itemValid){
        $("."+searchbarInputer.attr("id")+"Container div")[currentItem].click();
      } else {
        reinitOrderItemData();
      }
    }
  });

  //Function to scroll easy with directions touch
  function reverserFunction(listLength){
    //Upper part
    if (reverser < 1){
      reverser = 0;
      scroller -= 42;
      if (scroller < 0){
        scroller = 0;
      }
    //Downer part
    } else if(reverser>3){
      reverser = 4;
      scroller += 42
      if (scroller > (listLength - 4)*42){
        scroller = (listLength - 4)*42;
      }
    }
    $("."+searchbarInputer.attr("id")+"Container").scrollTop(scroller);
  }

  function currentItemSelect(currentItem){
    let listDiv = $("."+searchbarInputer.attr("id")+"Container div").get();
    listDiv.forEach(element => {
      element.classList.remove("currentItem");
    });
    listDiv[currentItem].classList.add("currentItem");
  }

  function deleteListDiv(){
    let listDiv = $("."+searchbarInputer.attr("id")+"Container div").get();
    listDiv.forEach(element => {
      element.remove();
    });
  }
}