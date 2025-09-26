function reacter(dataSubmit){
  const isParent = $(dataSubmit[1]).parents()[2];
  const dataMatching = dataSubmit[0];
  if (isParent.classList.contains("orderCreator")){
    $(".orderChoice .product span").text(dataMatching.name);
    $(".orderChoice .price span").text(dataMatching.price);
    $(".orderChoice .publicPrice span").text(dataMatching.publicPrice);
    $(".orderChoice .peromptAt span").text(dataMatching.peromptAt);
    $(".orderChoice .quantity input").val(0).focus().select();
  } else {
    console.log("Nosé passa");
  }
}

$(".orderCreator .searchbarAutocompleteContainer div").on("click", function(){
  console.log("Yawé");
});

//Set the autofocus on
$(".orderCreator .searchbarAutocomplete input").focus();

//Initialise the searchbarAutocomplete
searchbarAutocomplete($(".orderCreator .searchbarAutocomplete input"), jsProduct);
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
        matchingItem.innerHTML = element.name;
        //Event to appy when the user click on the matching item
        $(matchingItem).on("click", function(e){
          $(searchbarInputer).val(e.target.innerText);
          reacter([element, this]);
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
      $("."+searchbarInputer.attr("id")+"Container div")[currentItem].click();
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