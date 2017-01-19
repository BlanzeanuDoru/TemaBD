class Product {
    constructor(name, price) {
        this.name = name;
        this.price = price;
    }
}



$(document).ready(function(){
    var mycart = [];    
    
    $("button.to-cart").on("click", function() {
        var name = $(this).parent().parent().children("div.information").children("span.name").text();
        var price = $(this).parent().parent().children("div.information").children("span.price").text();
        var id = $(this).parent().parent().attr("prodid");

        mycart.push(new Product(name, price));
        var times = 0;
        
        for(var i=0; i<mycart.length; i++) {
            if (mycart[i].name == name && mycart[i].price == price) {
                times++;
            }
        }
        
        $obj = $("<li class=\"added\"><a class=\"in-cart-product\"><div prodid=\"" + id + "\">" + name + " " + times + "x" + price + "lei<span class=\"glyphicon glyphicon-remove remove\"></span></div></a></li>");
        $("div.cart-dropdown ul").prepend($obj);
        $("div.cart-dropdown ul li.total a").text("Total: " + mycart.length + " produse");

    });

    $("div.cart-dropdown ul.dropdown-menu").on("click", "li.added",function(){
        $(this).remove();
        var id = $(this).parent().attr('prodid');
        for(var i=0; i<mycart.length; i++) {
            if (mycart[i].id === id) {
                mycart.splice(i, 1);
            }
        }
        $("div.cart-dropdown ul li.total a").text("Total: " + mycart.length + " produse");
    });

    $("button.checkout").on("click", function() {
        if (mycart.length > 0) {
            $id = $("div.cart-dropdown ul li a div");
            var total_price = 0;
            for(var i=0; i<mycart.length; i++) {
                total_price += parseFloat(mycart[i].price);
                $obj = $("<input type=\"text\" value=\"" + $($id[i]).attr("prodid") + "\" name=\"item" + i +"\">");
                $("form.hidden-form").prepend($obj);
            }
            $obj = $("<input type=\"text\" value=\"" + total_price + "\" name=\"price\">");
            $("form.hidden-form").prepend($obj);
            $("form.hidden-form input[type=\"submit\"]").trigger("click");
        }
    });

    $("input.search_text").on("input", function() {
        var val = $("input.search_text").val().toLowerCase();
        $produse = $("div.produs");
        $produse.hide();
        for(var i=0; i<$produse.length; i++) {
            var name = $($produse[i]).children("div.information").children("span.name").text().toLowerCase();
            if (name.includes(val)) {
                $($produse[i]).show();
            }
        }     

    });


   $("tr").on("click", function() {
      var time = $(this).children("td:nth-child(1)").text();
      $("div.details").hide();
      var obj = $("div.details[time=\"" + time + "\"]");
      $("div.cont").append(obj);
      $(obj).show();

   });

});