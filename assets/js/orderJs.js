$(".orderSaved a").on("click", function(e){
  e.preventDefault();
  let data = JSON.parse(e.target.id);
  let title = 'Détails de la commande N°'+data.orderNumber+" du "+data.orderDate;
  let orderContent = $(".orderContent .list-group");
  //remove all list-group-item active class
  $(".orderSaved .list-group-item").get().forEach(element => {
    element.classList.remove("active");
  });
  //Active the current item
  $(e.target).parent().get()[0].classList.add("active");

  //Find and delete all list-group-items
  let orderContentItem = orderContent.find(".list-group-item").get();
  if (orderContentItem.length > 0){
    orderContentItem.forEach(element => {
      element.remove();
    });
  }
  $(".orderContent .card-header").text(title);

  //Creation of list-group-item
  data.productList.forEach(element => {
    let listGroupItem = document.createElement("div");
    listGroupItem.classList.add("list-group-item")
    $(listGroupItem).text(element.name)
    orderContent.append(listGroupItem);
  });
  console.log(data.productList);
});