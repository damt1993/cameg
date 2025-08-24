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
$(document).on("click", ".quantity span button", function (e) { 
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
});
