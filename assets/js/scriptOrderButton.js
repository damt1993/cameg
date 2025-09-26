//Function to up or down quantity
$(".quantity span button").click(function (e) { 
    e.preventDefault();
    let buttonClass = e.target.classList;
    let input = e.target.parentElement.parentElement.querySelector("input");
    
    if (buttonClass[2]==="remove" && input.value > 0){
        input.focus();
        input.value--;
    } else if (buttonClass[2]==="add" && input.value >= 0){
        input.focus();
        input.value++;
    }
});

//Function to up or down quantity pour les éléments créés via ajax
$(document).on("click", ".newQuantity span button", function (e) { 
    e.preventDefault();
    let buttonClass = e.target.classList;
    let input = e.target.parentElement.parentElement.querySelector("input");
    
    if (buttonClass[2]==="remove" && input.value > 0){
        input.focus();
        input.value--;
    } else if (buttonClass[2]==="add" && input.value >= 0){
        input.focus();
        input.value++;
    }
});


//function to set selected label
$("select[id=order_form_product]").change(function (e) { 
    e.preventDefault();
    let choice = e.target.value;
    for (let i = 0; i < jsProduct.length; i++) {
        const element = jsProduct[i];
        if (element.id == choice) {
            $(".product span").text(element.name);
            $(".price span").text(element.price+" F Cfa");
            $(".publicPrice span").text(element.publicPrice+" F Cfa");
            $(".peromptAt span").text(element.peromptAt);
            $(".orderChoice .quantity input").val(0).focus().select();
            $(".product span").attr("dataList", i);
            break;
        }
    }
});

//function to get selected product data
$(".orderChoice form").submit(function (e) { 
    e.preventDefault();
    const submitter = e.target;
    const input = $(submitter).find('.quantity input').val();
    const orderAdder = $(".card-body");
    const productListNumber = $(".product span").attr("dataList");
    let product = jsProduct[productListNumber];
    product.quantity = input;

    $.post(
        "/customer/customerorder/update",
        product,
        function (data) {
            orderAdder.prepend(data['blocCode']);
        },
        "json"
    );

    //Remise à zéro des valeurs de sélectio
    $(".orderChoice form").get(0).reset();
    $(".orderChoice select option").val("");
});

$(".searchbar .dropdown input").on("input", function(e){
    const inputValue = e.target.value;
    const parentElement = $(".searchbar .dropdown .dropdown-menu");
    
    //Remove dropdown items
    const elementsToRemove = $(".searchbar .dropdown li");
    elementsToRemove.get().forEach(element => {
        element.remove();
    });

    if(inputValue){
        const productList = jsProduct;
        const matchingProducts = productList.filter(productListItem => productListItem.name.toLowerCase().includes(inputValue.toLowerCase()));

        if (matchingProducts.length == 0){
            $(parentElement.append("<li class='dropdown-item disabled'>Aucun produit correspondant</li>"));
        } else {
            if (inputValue.length >= 3){
                $(parentElement.append("<li class='dropdown-header'>Resultats des produits</li>"));
                $(parentElement.append("<li class='dropdown-divider'></li>"));
                $.each(matchingProducts, function (indexInArray, valueOfElement) { 
                    $(parentElement).append("<li class='dropdown-item' value='"+valueOfElement.id+"'>"+valueOfElement.name+"</li>");
                });
            } else {
                $(parentElement.append("<li class='dropdown-item disabled'>Veuillez saisir au moins 3 caractères</li>"));
            }
        }
     
    } else {
        $(parentElement.append("<li class='dropdown-item disabled'>Veuillez saisir le nom d'un produit</li>"));
    }

});

$(".searchbar1 .dropdown input").on("input", function(e){
    const inputValue = e.target.value;
    const parentElement = $(".searchbar1 .dropdown .dropdown-menu");
    
    //Remove dropdown items
    const elementsToRemove = $(".searchbar1 .dropdown option");
    elementsToRemove.get().forEach(element => {
        element.remove();
    });

    if(inputValue){
        const productList = jsProduct;
        const matchingProducts = productList.filter(productListItem => productListItem.name.toLowerCase().includes(inputValue.toLowerCase()));

        if (matchingProducts.length == 0){
            $(parentElement.append("<option class='dropdown-item disabled'>Aucun produit correspondant</option>"));
        } else {
            if (inputValue.length >= 3){
                $(parentElement.append("<option class='dropdown-header'>Resultats des produits</option>"));
                $(parentElement.append("<option class='dropdown-divider'></option>"));
                $.each(matchingProducts, function (indexInArray, valueOfElement) { 
                    $(parentElement).append("<option class='dropdown-item' value='"+valueOfElement.id+"'>"+valueOfElement.name+"</option>");
                });
            } else {
                $(parentElement.append("<option class='dropdown-item disabled'>Veuillez saisir au moins 3 caractères</option>"));
            }
        }
     
    } else {
        $(parentElement.append("<option class='dropdown-item disabled'>Veuillez saisir le nom d'un produit</option>"));
    }

});

const countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh",
                    "Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia","Turkey","Turkmenistan","Turks &amp; Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];

function autocomplete(inputing, arrayList){
    let currentFocus;

    //If something is enter in the field this function is runing
    inputing.addEventListener("input", function(){
        let a, b, i, val = this.value;

        //Close all list
        closeAllLists();

        //If nothing input return false and attribut val the value -1
        if (!val){
            return false;
        }
        currentFocus = -1;

        //Create div that will contain items
        a = document.createElement('div');
        a.setAttribute("id", this.id+"autocomplete-list");
        a.classList.add("autocomplete-items");
        //a = "<div id='"+this.id+"autocomplete-list' class='autocomplet-items'></div>";
        this.parentNode.appendChild(a);

        //Find Matching items
        const matchingElement = arrayList.filter(item => item.toLowerCase().includes(inputing.value.toLowerCase()));

        //For each element in the array
        for (let i = 0; i < matchingElement.length; i++) {
            const element = matchingElement[i];
            //Create a Div
            b = document.createElement('div');
            //Make the matching letter in bold
            b.innerHTML = "<strong>"+element.substr(0, val.length)+"</strong>"+element.substr(val.length);

            //Create a field who will contain the value of each matching value
            b.innerHTML += "<input type='hidden' value='"+element+"' id='iteming'>";

            //include a function, when someone click on this div
            b.addEventListener("click", function(e){
                //Insert the value of the div in the input area
                //inputing.value = this.querySelector("input").value;
                 inputing.value = this.querySelector('#iteming').value;

                //Close the list
                closeAllLists();
            });
            a.appendChild(b);
        }
    });


    //Excecute a function press a key on keyboard
    inputing.addEventListener('keydown', function(e){
        let x = document.getElementById(this.id+"autocomplete-list");
        if(x){
            x = x.getElementsByTagName("div");
        }
        //Ik key press == key up increase
        if (e.keyCode == 40){
            addActive(x);
            currentFocus++;
        } else if (e.keyCode==38){
            //decrease
            currentFocus--;
            addActive(x);
        } else if (e.keyCode ==13){
            //Enter to submit
            e.preventDefault();

            if (currentFocus > -1){
                //Simulate clique on active item
                if (x){
                    x[currentFocus].click();
                }
            }
        }
    });

    function addActive(x){
        //A function to designate a item like active
        if (!x){
            return false;
        }

        //Remove all autocomplet-div active classe
        removeActive(x);

        //If the currentFocus is out of list, return focus to the firs cautocomplete-div
        if (currentFocus>= x.length){
            currentFocus=0;
        //If currentFocus is under 0 return the focus to the last autocomplete-div
        } else if (currentFocus<0){
            currentFocus = x.length - 1;
        }

        //Atribute active class to the current autocomplete-div
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x){
        //This function remove all autocomplete-active in the list
        for (let i = 0; i < x.length; i++) {
            const element = x[i];
            element.classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(element){
        //Close all autocomplete list in the document excepted the one passed in the argument
        const x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
            const elementItem = x[i];
            if (element!= elementItem && element!= inputing){
                elementItem.parentNode.removeChild(elementItem);
            }
            
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

autocomplete(document.getElementById("search-autocomplete"), countries);


function searchProduct(inputField, productList){

    //Index of autoselect product
    let autoFocus = -1;

    //Event on pressingkey
    inputField.on("input", function () {
        //Because of risque to have multiple div create at each keypress, remove the div who was create
        removeAllProductListDiv();
        //Get the input value
        const inputValue = this.value;
        if (!inputValue){
            return;
        }

        //Find the product in the list who match with our input value
        let matchingProductsList = jsProduct.filter(product=>product.name.toLowerCase().includes(inputValue.toLowerCase()));

        //Check if we have matching items
        if (matchingProductsList.length<1){
            return;
        }
        //Create the div to show the matching products
        let productListDiv = document.createElement('div');
        productListDiv.setAttribute("id", this.id+"List");
        productListDiv.classList.add(this.id+"List");

        //Add the new div create to the parent div off our input field
        this.parentNode.appendChild(productListDiv);

        //Create a variable to stock the list of elements
        matchingProductsList.forEach(element => {
            const matchingItem = document.createElement("div");
            matchingItem.innerHTML = element.name;

            //Add a click event to each item
            $(matchingItem).click(function (e) { 
                inputField.val(e.target.innerHTML);
                removeAllProductListDiv();
                $(".orderChoice .product span").text(element.name);
                $(".orderChoice .price span").text(element.price);
                $(".orderChoice .publicPrice span").text(element.publicPrice);
                $(".orderChoice .peromptAt span").text(element.peromptAt);
                $(".orderChoice .quantityField input").focus().select();
            });
            productListDiv.appendChild(matchingItem);
        });
    });

    function removeAllProductListDiv(elements){
        let listProductListDiv = $(".searchProductList").get();
        listProductListDiv.forEach(element => {
            element.parentNode.removeChild(element);
        });
    }

    let scroller = 0;
    //Add event to use direction button on keybord end enter
    inputField.on("keyup", function(e){
        let listProductListDiv = document.querySelectorAll(".searchProductList div");

        let listLength = listProductListDiv.length;
        //Just use this event if we have matching items
        if(listLength>0){
            if(e.keyCode==40){
                autoFocus++;
                if (autoFocus==listLength){
                    autoFocus = 0;
                    $(".searchProductList").scrollTop(0);
                    scroller = 0;
                }
                itemIndexing(autoFocus, listLength);
                if (autoFocus>3){
                    scroller += 37;
                    $(".searchProductList").scrollTop(scroller);
                }
            } else if (e.keyCode==38){
                autoFocus--;
                if(autoFocus<0){
                    autoFocus = listLength-1;
                    scroller = 37*listLength;
                }
                itemIndexing(autoFocus, listLength);
                scroller -= 37;
                $(".searchProductList").scrollTop(scroller);
                if(scroller<0){
                    scroller = 0;
                }
            }

            if(e.keyCode==13){
                if (listProductListDiv){
                    listProductListDiv[autoFocus].click();
                }
            }
        }
    });

    function itemIndexing(index, length){
        let listProductListDiv = document.querySelectorAll(".searchProductList div");
        listProductListDiv.forEach(element => {
            element.classList.remove("productIndexing")
        });

        listProductListDiv[index].classList.add("productIndexing")
    }
}

searchProduct($(".searchProduct input"), jsProduct);
