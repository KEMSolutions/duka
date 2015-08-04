var billingContainer={autoFillBillingAddress:function(t,e){if($(".billing-checkbox").is(":checked")){$("#billing"+e[0].id.substring("shipping".length,e[0].id.length)).val(e[0].value);for(var i=0,a=t.length;a>i;i++)if(t[i][0].id.indexOf("shipping")>-1){var n=t[i][0].id.substring("shipping".length,t[i][0].id.length);$("#billing"+n).val(t[i][0].value)}}},setDifferentBillingAddress:function(t){$(".billing-checkbox").on("change",function(){$(".form-billing .chosen-container").width($("#customer_email").outerWidth()-20),this.checked?$(".form-billing").fadeOut(function(){$(this).addClass("hidden")}):($(".form-billing").hide().removeClass("hidden").fadeIn(),t.clearBillingAddress())})},clearBillingAddress:function(){""!=$(".form-billing input").val()&&""==$(".form-billing input").val()},init:function(){var t=billingContainer;t.setDifferentBillingAddress(t)}},estimateContainer={ajaxCall:function(){$.ajax({type:"POST",url:ApiEndpoints.estimate,data:{email:$("#customer_email").val(),shipping:{},products:UtilityContainer.getProductsFromLocalStorage(),shipping_address:UtilityContainer.getShippingFromForm()},success:function(t){console.log(t),estimateContainer.init(t)},error:function(t,e){return 403==t.status?void window.location.replace("/auth/login"):void $("#estimate").html('<div class="alert alert-danger">Une erreur est survenue. Veuillez vérifier les informations fournies.</div>')}})},getShipmentTaxes:function(t,e){for(var i=0,a=0;a<e.shipping.services.length;a++)if(e.shipping.services[a].method==t&&0!=e.shipping.services[a].taxes.length)for(var n=0;n<e.shipping.services[a].taxes.length;n++)i+=e.shipping.services[a].taxes[n].amount;return i.toFixed(2)},displayEstimatePanel:function(){$("#estimate").removeClass("hidden fadeOutUp").addClass("animated fadeInDown")},scrollTopToEstimate:function(){$("html, body").animate({scrollTop:$("#estimate").offset().top},1e3)},fetchEstimate:function(t,e){$(".has-error").removeClass("has-error");for(var i=($("#customer_email").val(),$("#shippingPostcode").val(),$(".country").val(),0),a=t.shipping.services.length;a>i;i++){var n="<tr data-service='"+t.shipping.services[i].method+"'><td>"+t.shipping.services[i].name+"</td><td>"+t.shipping.services[i].transit+"</td><td>"+t.shipping.services[i].delivery+"</td><td>"+t.shipping.services[i].price+"</td><td><input type='radio' name='shipping' class='shipping_method' data-taxes='"+e.getShipmentTaxes(t.shipping.services[i].method,t)+"' data-cost='"+t.shipping.services[i].price+"' data-value='"+t.shipping.services[i].method+"' value='"+btoa(JSON.stringify(t.shipping.services[i]))+"' ></td>";$("#estimate .table-striped").append(n)}$("#estimateButton").removeClass("btn-three").addClass("btn-one").text(Localization["continue"]),e.selectDefaultShipmentMethod(),e.scrollTopToEstimate(),paymentContainer.init(t)},selectDefaultShipmentMethod:function(){for(var t=["DOM.EP","USA.TP","INT.TP"],e=$("input[name=shipping]"),i=0,a=e.length;a>i;i++)-1!=t.indexOf(e[i].dataset.value)&&(e[i].checked=!0)},init:function(t){var e=estimateContainer;0==UtilityContainer.getProductsFromLocalStorage().length?location.reload():(e.displayEstimatePanel(),e.fetchEstimate(t,e))}},locationContainer={populateCountry:function(t){var e="/js/data/country-list."+t+".json",i="",a=$(".country");$.getJSON(e,function(t){$.each(t,function(t,e){i+="CA"==t?"<option value='"+t+"' selected>"+e+"</option>":"<option value='"+t+"'>"+e+"</option>"}),a.append(i)}).done(function(){$(".country").chosen()})},populateProvincesAndStates:function(t,e){$.getJSON("/js/data/world-states.json",function(i){for(var a=0,n=t.length;n>a;a++){var o="",r=$(".province").find("[data-country='"+t[a]+"']");$.each(i,function(e){i[e].country===t[a]&&"QC"==i[e]["short"]?o+="<option value='"+i[e]["short"]+"' selected>"+i[e].name+"</option>":i[e].country===t[a]&&(o+="<option value='"+i[e]["short"]+"'>"+i[e].name+"</option>")}),r.append(o)}e()})},updateChosenSelects:function(t,e){"CA"==t||"US"==t||"MX"==t?$(e).removeAttr("disabled").trigger("chosen:updated"):$(e).attr("disabled","disabled"),$(e+" optgroup").attr("disabled","disabled"),("CA"==t||"US"==t||"MX"==t)&&$(e+' [data-country="'+t+'"]').removeAttr("disabled"),$(e).trigger("chosen:updated")},callUpdateChosenSelects:function(t){$("#billingCountry").on("change",function(){t.updateChosenSelects($(this).val(),"#billingProvince")}),$("#shippingCountry").on("change",function(){t.updateChosenSelects($(this).val(),"#shippingProvince")})},init:function(){var t=locationContainer;t.populateCountry($("html").attr("lang")),t.populateProvincesAndStates(["CA","US","MX"],function(){$(".province").chosen()}),t.callUpdateChosenSelects(t)}},paymentContainer={displayPaymentPanel:function(){$("#payment").removeClass("hidden fadeOutUp").addClass("animated fadeInDown"),$("#checkoutButton").addClass("animated rubberBand")},initPaymentPanel:function(t){var e=parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2),i=$("input:radio.shipping_method:checked").data("cost"),a=paymentContainer.getTaxes(t)+parseFloat($("input:radio.shipping_method:checked").data("taxes")),n=parseFloat(e)+parseFloat(i)+parseFloat(a);$("#price_subtotal").text(e),$("#price_transport").text(i),$("#price_taxes").text(a.toFixed(2)),$("#price_total").text(n.toFixed(2))},updatePaymentPanel:function(t){var e,i,a=parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()).toFixed(2);$(".shipping_method").on("change",function(){e=$(this).data("cost"),i=paymentContainer.getTaxes(t)+parseFloat($(this).data("taxes")),total=parseFloat(a)+parseFloat(e)+parseFloat(i),$("#price_subtotal").text(a),$("#price_transport").text(e),$("#price_taxes").text(i.toFixed(2)),$("#price_total").text(total.toFixed(2))})},getTaxes:function(t){var e=0,i=t.taxes.length;if(0!=i)for(var a=0;i>a;a++)e+=t.taxes[a].amount;return e},init:function(t){paymentContainer.displayPaymentPanel(),paymentContainer.initPaymentPanel(t),paymentContainer.updatePaymentPanel(t),checkoutLogicContainer.init()}},cartDisplayContainer={$el:{$back:$("#back"),$proceed:$("#proceed"),$trigger:$(".view-cart"),$container:$("#cart-container"),$checkout:$("#checkout"),$body:$("body")},displayOn:function(){var t=cartDisplayContainer.$el.$container.width();cartDisplayContainer.$el.$container.css({"margin-right":-t}),cartDisplayContainer.$el.$trigger.click(function(){cartDisplayContainer.animateIn()})},displayOff:function(){cartDisplayContainer.$el.$back.click(function(){cartDisplayContainer.animateOut()}),cartDisplayContainer.$el.$checkout.click(function(){sessionStorage.isDisplayed=!1})},animateIn:function(){cartDisplayContainer.$el.$container.show(),cartDisplayContainer.$el.$container.animate({"margin-right":0},400),sessionStorage.isDisplayed=!0},animateOut:function(){var t=cartDisplayContainer.$el.$container.width();cartDisplayContainer.$el.$container.animate({"margin-right":-t},400,function(){$(this).hide()}),sessionStorage.isDisplayed=!1},setCartItemsHeight:function(){cartDisplayContainer.computeCartItemsHeight(),$(window).on("resize",function(){cartDisplayContainer.computeCartItemsHeight()}),cartDisplayContainer.$el.$trigger.on("click",function(){cartDisplayContainer.computeCartItemsHeight()})},computeCartItemsHeight:function(){var t=$("#cart-container").height()-($(".cart-header").height()+$(".cart-footer").height());$("#cart-items").css("height",t)},init:function(){cartDisplayContainer.displayOn(),cartDisplayContainer.displayOff(),UtilityContainer.populateCountry($("html").attr("lang")),"true"==sessionStorage.isDisplayed&&(cartDisplayContainer.$el.$container.css("margin-right",0),cartDisplayContainer.$el.$container.show())}},headerContainer={md:{removeCartDescription:function(){$(window).width()<=1195&&($("#nav-right #cart-description").text(""),$("#nav-right").css("padding-bottom","18px"))}},sm:{btnTransform_sm:function(){$(window).width()<=934&&$(window).width()>=769&&($(".row:first .btn").addClass("btn-sm"),$("#searchBar").addClass("input-sm"),$("#view-cart-wrapper").addClass("btn-xs btn-xs-btn-sm-height"))}},changeTextFromDropdown:function(t){$(t+" .dropdown-menu li a").click(function(){$(t+" .btn:first-child").html($(this).text()+'<span class="caret"></span>'),$(t+" .btn:first-child").val($(this).text())})},semanticUI:{initDropdownModule:function(){$(".ui.dropdown-select").dropdown(),$(".ui.dropdown-no-select").dropdown({action:"select"})}},init:function(){var t=headerContainer;$(window).on("load resize",function(){t.md.removeCartDescription(),t.sm.btnTransform_sm()}),t.changeTextFromDropdown(".search-filter"),t.semanticUI.initDropdownModule()}},paymentOverlayContainer={cancelOrder:function(){$("body").on("click","#cancelOrder",function(){Cookies.remove("_unpaid_orders"),$("#cancelledOrder .jumbotron").fadeOut(),window.location.replace("/"),UtilityContainer.removeAllProductsFromLocalStorage()})},checkPendingOrders:function(){if(Cookies.get("_unpaid_orders")){var t=JSON.parse(Cookies.get("_unpaid_orders"));$.ajax({type:"GET",url:ApiEndpoints.orders.view.replace(":id",t.id).replace(":verification",t.verification),success:function(t){"pending"==t.status?paymentOverlayContainer.showPaymentNotice():Cookies.remove("_unpaid_orders")}})}},showPaymentNotice:function(){var t=JSON.parse(Cookies.get("_unpaid_orders"));$("body").prepend('<div class="container overlay fullScreen" id="cancelledOrder"><div class="jumbotron vertical-align color-one"><div class="text-center"><h2>'+Localization.pending_order.replace(":command",t.id)+"</h2><h4>"+Localization.what_to_do+'</h4><br /><ul class="list-inline"><li><a href="'+ApiEndpoints.orders.pay.replace(":id",t.id).replace(":verification",t.verification)+'"><button class="btn btn-success" id="payOrder">'+Localization.pay_now+'</button></a></li><li><button class="btn btn-danger" id="cancelOrder">'+Localization.cancel_order+"</button></li></ul></div></div></div>")},init:function(){var t=paymentOverlayContainer;t.cancelOrder(),t.checkPendingOrders()}},productLayoutFavoriteContainer={fadeInFavoriteIcon:function(){$(".dense_product").hover(function(){$(this).children(".favorite-wrapper").fadeIn()},function(){$(this).children(".favorite-wrapper").hide()})},setWishlistBadgeQuantity:function(){var t=UtilityContainer.getNumberOfProductsInWishlist();$(".wishlist_badge").text(t)},addToFavorite:function(){var t,e=productLayoutFavoriteContainer;$(".favorite-wrapper").on("click",function(){$(this).hasClass("favorited")?e.removeFromFavorite($(this),e):(t=UtilityContainer.buyButton_to_Json($(this).parent().find(".buybutton")),localStorage.setItem("_wish_product "+t.product,JSON.stringify(t)),$(this).addClass("favorited"),e.setWishlistBadgeQuantity())})},persistFavorite:function(){for(var t=0,e=localStorage.length;e>t;t++)if(0===localStorage.key(t).lastIndexOf("_wish_product",0))for(var i=0;i<$(".favorite-wrapper").length;i++)JSON.parse(localStorage.getItem(localStorage.key(t))).product===parseInt($(".favorite-wrapper")[i].dataset.product)&&($(".favorite-wrapper")[i].className+=" favorited")},removeFromFavorite:function(t,e){t.removeClass("favorited"),localStorage.removeItem("_wish_product "+t.data("product")),e.setWishlistBadgeQuantity()},init:function(){var t=productLayoutFavoriteContainer;t.addToFavorite(),t.persistFavorite(),t.fadeInFavoriteIcon(),t.setWishlistBadgeQuantity()}},categoryContainer={searchParameters:{page:1,per_page:8,order:"relevance",min_price:null,max_price:null,brands:"",categories:""},blurBackground:function(){$(".category-header").blurjs({source:".category-header"})},itemsPerPage:function(){$(".items-per-page .item").on("click",function(){categoryContainer.URL_add_parameter("per_page",$(this).data("sort"))}),$("#items-per-page-box").dropdown("set selected",this.searchParameters.per_page)},sortBy:function(){$(".sort-by .item").on("click",function(){categoryContainer.URL_add_parameter("order",$(this).data("sort"))}),$("#sort-by-box").dropdown("set selected",this.searchParameters.order)},priceUpdate:function(){$("#price-update").on("click",function(){categoryContainer.URL_add_parameter("min_price",$("#min-price").val()),categoryContainer.URL_add_parameter("max_price",$("#max-price").val())}),this.searchParameters.min_price&&$("#min-price").val(this.searchParameters.min_price),this.searchParameters.max_price&&$("#max-price").val(this.searchParameters.max_price)},categoriesUpdate:function(){},brandsUpdate:function(){},toggleLayout:function(){var t=$(".layout-toggle-container"),e=$(".dense_product"),i=$(".product-image"),a=$(".dense_product .buybutton");$("#list-layout, #grid-layout").on("click",function(){t.hasClass("grid-layout")?(t.removeClass("grid-layout").addClass("list-layout"),e.removeClass("col-xs-6 col-sm-4 col-md-3 text-center no-border").addClass("col-xs-12 col-sm-12 col-md-12 border-bottom padding-1"),i.removeClass("img-responsive center-block").addClass("pull-left").css("margin-right","5%"),a.css("margin-top","3%"),$(this).toggleClass("active")):t.hasClass("list-layout")&&(t.removeClass("list-layout").addClass("grid-layout"),e.removeClass("col-xs-12 col-sm-12 col-md-12 border-bottom padding-1").addClass("col-xs-6 col-sm-4 col-md-3 text-center no-border"),i.addClass("img-responsive center-block").removeClass("pull-left").css("margin-right","0"),a.css("margin-top","0"),$(this).toggleClass("active"))})},URL_add_parameter:function(t,e){t=escape(t),e=escape(e);var i=document.location.search.substr(1).split("&");if(""==i)document.location.search="?"+t+"="+e;else{for(var a,n=i.length;n--;)if(a=i[n].split("="),a[0]==t){a[1]=e,i[n]=a.join("=");break}0>n&&(i[i.length]=[t,e].join("=")),document.location.search=i.join("&")}},retrieveSearchParameters:function(){var t=document.location.search.substr(1);if(!(t.length<1)){var e,i,a,n,o=t.split("&");for(e in o)o[e].indexOf("=")<1||(i=o[e].split("="),a=i[0],n=i[1],"undefined"!=typeof this.searchParameters[a]&&(this.searchParameters[a]=n))}},init:function(){var t=categoryContainer;t.retrieveSearchParameters(),t.blurBackground(),t.itemsPerPage(),t.sortBy(),t.priceUpdate(),t.categoriesUpdate(),t.brandsUpdate(),t.toggleLayout()}},wishlistContainer={setNumberOfProductsInHeader:function(){var t="";t+=0==UtilityContainer.getNumberOfProductsInWishlist()||1==UtilityContainer.getNumberOfProductsInWishlist()?UtilityContainer.getNumberOfProductsInWishlist()+"  item ":UtilityContainer.getNumberOfProductsInWishlist()+"  items ",$("#quantity-wishlist").text(t)},init:function(){var t=wishlistContainer;t.setNumberOfProductsInHeader()}},UtilityContainer={getProductsFromLocalStorage:function(){for(var t=[],e=0,i=localStorage.length;i>e;e++)if(0===localStorage.key(e).lastIndexOf("_product",0)){var a=JSON.parse(localStorage.getItem(localStorage.key(e))),n=a.product,o=a.quantity,r=a.price;t.push({id:n,quantity:o,price:r})}return t},getNumberOfProductsInWishlist:function(){for(var t=0,e=0,i=localStorage.length;i>e;e++)0===localStorage.key(e).lastIndexOf("_wish_product",0)&&(t+=JSON.parse(localStorage.getItem(localStorage.key(e))).quantity);return t},getNumberOfProducts:function(){for(var t=0,e=0,i=localStorage.length;i>e;e++)0===localStorage.key(e).lastIndexOf("_product",0)&&(t+=JSON.parse(localStorage.getItem(localStorage.key(e))).quantity);return t},getProductsPriceFromLocalStorage:function(){for(var t=0,e=UtilityContainer.getProductsFromLocalStorage(),i=0,a=e.length;a>i;i++)t+=e[i].price*e[i].quantity;return t},removeAllProductsFromLocalStorage:function(){for(var t=0,e=localStorage.length;e>t;t++)0===localStorage.key(t).lastIndexOf("_product",0)&&localStorage.removeItem(localStorage.key(t))},getShippingFromForm:function(){return res={country:$("#shippingCountry").val(),postcode:$("#shippingPostcode").val(),province:$("#shippingProvince").val(),line1:$("#shippingAddress1").val(),line2:$("#shippingAddress2").val(),name:$("#shippingFirstname").val()+" "+$("#shippingLastname").val(),city:$("#shippingCity").val(),phone:$("#shippingTel").val()}},buyButton_to_Json:function(t){return{product:t.data("product"),name:t.data("name"),price:t.data("price"),thumbnail:t.data("thumbnail"),thumbnail_lg:t.data("thumbnail_lg"),quantity:parseInt(t.data("quantity")),link:t.data("link")}},populateCountry:function(t){var e="/js/data/country-list."+t+".json",i="",a=$("#country");$.getJSON(e,function(t){$.each(t,function(t,e){i+="CA"==t?"<option value='"+t+"' selected>"+e+"</option>":"<option value='"+t+"'>"+e+"</option>"}),a.append(i)})},validateEmptyFields:function(t){for(var e=!0,i=0,a=t.length;a>i;i++)if(""==t[i].val()){e=!1;break}return e},validateEmail:function(t){var e=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;return e.test(t)},validatePostCode:function(t,e){return"CA"==e?t.match(/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} ?\d{1}[A-Z]{1}\d{1}$/i)?!0:!1:"US"==e?t.match(/^\d{5}(?:[-\s]\d{4})?$/)?!0:!1:!0},validateEmptyCart:function(){var t;return t=0===UtilityContainer.getProductsPriceFromLocalStorage()?!0:!1},addErrorClassToFields:function(t){for(var e=0,i=t.length;i>e;e++)""==t[e].val()&&(t[e].parent().addClass("has-error"),t[e].addClass("animated shake").bind("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$(this).removeClass("animated"),$(this).removeClass("shake"),$(this).unbind()}))},addErrorClassToFieldsWithRules:function(t){t.parent().addClass("has-error"),t.addClass("animated shake").bind("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$(this).removeClass("animated"),$(this).removeClass("shake"),$(this).unbind()})},addFadeOutUpClass:function(t){t.addClass("animated fadeOutUp").delay(1e3).queue(function(){$(this).addClass("hidden").clearQueue()})},removeErrorClassFromFields:function(t){for(var e=0,i=t.length;i>e;e++)""!=t[e].val()&&t[e].parent().hasClass("has-error")&&t[e].parent().removeClass("has-error")},getCheapestShippingMethod:function(t){for(var e=t.shipping.services,i=[],a=0,n=e.length;n>a;a++)i.push(e[a]);return i.sort(function(t,e){return t.price-e.price}),{fare:i[0].price,method:i[0].method}},getTaxes:function(t){var e=0,i=t.taxes.length;if(0!=i)for(var a=0;i>a;a++)e+=t.taxes[a].amount;return e.toFixed(2)},getShipmentTaxes:function(t,e){var i=0;console.log(e);for(var a=0;a<e.shipping.services.length;a++)if(e.shipping.services[a].method==t&&0!=e.shipping.services[a].taxes.length)for(var n=0;n<e.shipping.services[a].taxes.length;n++)i+=e.shipping.services[a].taxes[n].amount;return i.toFixed(2)},getCartTaxes:function(t,e){var i=parseFloat(UtilityContainer.getTaxes(e)),a=parseFloat(UtilityContainer.getShipmentTaxes(t,e)),n=i+a;return n},getCartTotal:function(t,e){var i=parseFloat(UtilityContainer.getCartTaxes(t.method,e)),a=parseFloat(UtilityContainer.getCheapestShippingMethod(e).fare),n=parseFloat(UtilityContainer.getProductsPriceFromLocalStorage()),o=(i+a+n).toFixed(2);return o}},checkoutInitContainer={estimateButtonClick:function(){$("#estimateButton").on("click",function(t){var e=$("#customer_email"),i=$("#customer_phone"),a=$("#shippingFirstname"),n=$("#shippingLastname"),o=$("#shippingAddress1"),r=$("#shippingCity"),s=$("#shippingCountry").val(),c=($("#shippingPostcode"),$("#billingFirstname")),l=$("#billingLastname"),d=$("#billingAddress1"),u=$("#billingCity"),p=$("#billingCountry").val(),m={country:s,postcode:$("#shippingPostcode").val(),postcodeInput:$("#shippingPostcode")},h=[a,n,o,r,c,l,d,u,e,i];t.preventDefault(),billingContainer.autoFillBillingAddress(h,m.postcodeInput);var g={country:p,postcode:$("#billingPostcode").val(),postcodeInput:$("#billingPostcode")};checkoutValidationContainer.init(h,e,m,g)})},init:function(){locationContainer.init(),billingContainer.init(),$("#shippingFirstname").focus();var t=checkoutInitContainer;t.estimateButtonClick()}},checkoutLogicContainer={createOrdersCookie:function(t){var e=t.id,i=t.verification;Cookies.set("_unpaid_orders",JSON.stringify({id:e,verification:i}))},placeOrderAjaxCall:function(t){$.ajax({method:"POST",url:ApiEndpoints.placeOrder,data:$("#cart_form").serialize(),cache:!1,success:function(e){console.log(e),t.createOrdersCookie(e),history.pushState({data:e},"Checkout ","/dev/cart"),window.location.replace(e.payment_details.payment_url)},error:function(t,e){console.log(t),console.log(e)}})},hidePanels:function(t){$(".quantity, #shippingPostcode, #shippingCity").on("change",function(){UtilityContainer.addFadeOutUpClass($("#estimate")),UtilityContainer.addFadeOutUpClass($("#payment")),t.updateEstimateButtonValue()}),$(".close-button").on("click",function(){UtilityContainer.addFadeOutUpClass($("#estimate")),UtilityContainer.addFadeOutUpClass($("#payment")),t.updateEstimateButtonValue()})},updateEstimateButtonValue:function(){$("#estimateButton").removeClass("btn-one animated rubberBand").addClass("animated rubberBand btn-three").text(Localization.update)},init:function(){var t=checkoutLogicContainer;$("#checkoutButton").on("click",function(e){e.preventDefault(),$("#checkoutButton").html('<i class="fa fa-spinner fa-spin"></i>'),t.placeOrderAjaxCall(t)}),t.hidePanels(t)}},checkoutValidationContainer={removeErrorClassFromEmail:function(t){UtilityContainer.validateEmail(t.val())&&t.parent().hasClass("has-error")&&t.parent().removeClass("has-error")},removeErrorClassFromPostcode:function(t,e){UtilityContainer.validatePostCode(t.val(),e)&&t.parent().hasClass("has-error")&&t.parent().removeClass("has-error")},init:function(t,e,i,a){var n=checkoutValidationContainer;UtilityContainer.validateEmptyFields(t)&&UtilityContainer.validateEmail(e.val())&&UtilityContainer.validatePostCode(i.postcode,i.country)&&UtilityContainer.validatePostCode(a.postcode,a.country)?($("#estimateButton").html('<i class="fa fa-spinner fa-spin"></i>'),$("#estimate .table-striped").children().length>0&&$("#estimate .table-striped tbody").empty(),estimateContainer.ajaxCall()):(UtilityContainer.addErrorClassToFields(t),UtilityContainer.validatePostCode(i.postcode,i.country)||UtilityContainer.addErrorClassToFieldsWithRules(i.postcodeInput),UtilityContainer.validatePostCode(a.postcode,a.country)||UtilityContainer.addErrorClassToFieldsWithRules(a.postcodeInput),UtilityContainer.validateEmail(e.val())||(UtilityContainer.addErrorClassToFieldsWithRules(e),$("#why_email").removeClass("hidden").addClass("animated bounceInRight").tooltip())),UtilityContainer.removeErrorClassFromFields(t),n.removeErrorClassFromEmail(e),n.removeErrorClassFromPostcode(i.postcodeInput,i.country),n.removeErrorClassFromPostcode(a.postcodeInput,a.country)}},cartLogicContainer={$el:{$list:$(".cart-items-list")},addItem:function(t){var e=(parseInt(t.quantity)*parseFloat(t.price)).toFixed(2),i='<li class="w-box animated bounceInDown" data-product="'+t.product+'" data-quantity=1><div class="col-xs-3 text-center"><img src='+t.thumbnail_lg+' class="img-responsive"></div><div class="col-xs-9 no-padding-left"><div class="row"><div class="col-xs-10"><h3 class="product-name">'+t.name+'</h3></div><div class="col-xs-2"><h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove Item</span></i></h4></div></div><div class="row"><div class="col-xs-8"><div class="input-group"><label for="products['+t.product+'][quantity]" class="sr-only">'+t.name+":"+t.price+'</label><input type="number" class="quantity form-control input-sm" min="1" step="1" value="'+t.quantity+'" name="products['+t.product+'][quantity]"><span class="input-group-addon update_quantity_indicator"><i class="fa" hidden><span class="sr-only">Update quantity</span></i></span></div></div><div class="col-xs-4 product-price text-right" data-price="'+t.price+'">$'+e+'<span class="sr-only">'+$+t.price+'</span></div></div><input type="hidden" name="products['+t.product+'][id]" value="'+t.product+'"/> </div></li>';$(".cart-items-list [data-product='"+t.product+"']").length||cartLogicContainer.$el.$list.append(i)},storeItem:function(t){localStorage.setItem("_product "+t.product,JSON.stringify(t)),cartLogicContainer.setBadgeQuantity(),cartLogicContainer.setQuantityCookie(),cartLogicContainer.setCartSubtotal(),cartLogicContainer.setCartTotal(),cartLogicContainer.updateAjaxCall()},loadItem:function(){for(var t=0,e=localStorage.length;e>t;t++)0===localStorage.key(t).lastIndexOf("_product",0)&&cartLogicContainer.addItem(JSON.parse(localStorage.getItem(localStorage.key(t))))},deleteItem:function(){$(document).on("click",".close-button",function(){$parent=$(this).closest(".animated").addClass("animated bounceOutLeft"),$parent.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$(this).remove()}),localStorage.removeItem("_product "+$(this).closest(".animated").data("product")),cartLogicContainer.setBadgeQuantity(),cartLogicContainer.setQuantityCookie(),cartLogicContainer.setCartSubtotal(),cartLogicContainer.setCartTotal(),cartLogicContainer.updateAjaxCall()})},modifyQuantity:function(){$("#cart-items").on("change",".quantity",function(){$container=$(this).closest("li"),$product_price=$container.find(".product-price"),$product_price.text("$"+($product_price.data("price")*$(this).val()).toFixed(2));var t=JSON.parse(localStorage.getItem("_product "+$container.data("product")));t.quantity=parseInt($(this).val()),localStorage.setItem("_product "+$container.data("product"),JSON.stringify(t)),cartLogicContainer.setBadgeQuantity(),cartLogicContainer.setQuantityCookie(),cartLogicContainer.setCartSubtotal(),cartLogicContainer.setCartTotal(),cartLogicContainer.updateAjaxCall()})},modifyQuantityBeforeBuying:function(){$("#item_quantity").on("change",function(){$(this).closest(".form-group").next().data("quantity",parseInt($(this).val()))})},setBadgeQuantity:function(){var t=UtilityContainer.getNumberOfProducts();$(".cart_badge").text(t)},setQuantityCookie:function(){var t=UtilityContainer.getNumberOfProducts();void 0==Cookies.get("quantityCart")||0===t?Cookies.set("quantityCart",btoa("0")):Cookies.set("quantityCart",btoa(t))},setCartSubtotal:function(){$("dd#subtotal").text("$"+UtilityContainer.getProductsPriceFromLocalStorage().toFixed(2))},setCartShipping:function(t){$("dd#shipping").text("$"+UtilityContainer.getCheapestShippingMethod(t).fare)},setCartTaxes:function(t){$("#taxes").text("$"+t.toFixed(2))},setCartTotal:function(t){$(".cart-total dl").show(),$(".calculation.total dd").text("$ "+t)},ajaxCall:function(){$.ajax({type:"POST",url:"/api/estimate",data:{products:UtilityContainer.getProductsFromLocalStorage(),shipping_address:{postcode:$("#postcode").val(),country:$(".price-estimate #country").val(),province:"QC"}},success:function(t){cartLogicContainer.setCartShipping(t),cartLogicContainer.setCartTaxes(UtilityContainer.getCartTaxes(UtilityContainer.getCheapestShippingMethod(t).method,t)),cartLogicContainer.setCartTotal(UtilityContainer.getCartTotal(UtilityContainer.getCheapestShippingMethod(t),t))},error:function(t){console.log(t)},complete:function(){$(".price-estimate").fadeOut(300,function(){$(".calculation.hidden").fadeIn().removeClass("hidden"),$(".cart-total.hidden").fadeIn().removeClass("hidden")})}})},updateAjaxCall:function(){$(".total").parent().hasClass("hidden")||($(".cart-total dl").hide(),$(".price-estimate-update").fadeIn("fast")),$(".changeLocation").click(function(){$("dl.calculation").addClass("hidden"),$(".getEstimate").html(Localization.calculate),$(".price-estimate-update").fadeOut(),$(".price-estimate").fadeIn()}),$(".price-estimate-update .getEstimate").click(function(){UtilityContainer.validateEmptyCart()||setTimeout(function(){$(".price-estimate-update .getEstimate").parent().fadeOut(300),$(".price-estimate-update .getEstimate").html(Localization.calculate)},2250)})},init:function(){cartLogicContainer.setBadgeQuantity(),cartLogicContainer.loadItem(),cartLogicContainer.deleteItem(),cartLogicContainer.modifyQuantity(),cartLogicContainer.modifyQuantityBeforeBuying(),cartLogicContainer.setQuantityCookie(),cartLogicContainer.setCartSubtotal()}},cartDrawerInitContainer={buyButtonClick:function(){$("body").on("click",".buybutton",function(){cartDisplayContainer.animateIn(),cartLogicContainer.addItem(UtilityContainer.buyButton_to_Json($(this))),cartLogicContainer.storeItem(UtilityContainer.buyButton_to_Json($(this))),$("#cart-items .empty-cart").addClass("hidden")})},getEstimateClick:function(){$(".getEstimate").on("click",function(){UtilityContainer.validatePostCode($("#postcode").val(),$(".price-estimate #country").val())&&UtilityContainer.validateEmptyFields([$("#postcode")])&&!UtilityContainer.validateEmptyCart()?($(this).html('<i class="fa fa-spinner fa-spin"></i>'),cartLogicContainer.ajaxCall()):UtilityContainer.validateEmptyCart()?$("#cart-items .empty-cart").removeClass("hidden"):UtilityContainer.addErrorClassToFieldsWithRules($("#postcode"))})},init:function(){cartDisplayContainer.init(),cartLogicContainer.init(),cartDisplayContainer.setCartItemsHeight();var t=cartDrawerInitContainer;t.buyButtonClick(),t.getEstimateClick()}},wishlistLogicContainer={createWishlistElement:function(t){var e=wishlistLogicContainer,i='<div class="col-md-12 list-layout-element"><div class="col-md-2"><img src='+t.thumbnail_lg+'></div><div class="col-md-10"><button class="btn btn-outline btn-danger-outline pull-right btn-lg inline-block padding-side-lg removeFavoriteButton" data-product="'+t.product+'">Remove from wishlist </button><button class="btn btn-success buybutton pull-right btn-lg inline-block padding-side-lg"data-product="'+t.product+'"data-price="'+t.price+'"data-thumbnail="'+t.thumbnail+'"data-thumbnail_lg="'+t.thumbnail_lg+'"data-name="'+t.name+'"data-quantity="'+t.quantity+'">Add to cart </button><a href='+t.link+'><h4 style="margin-top: 5px">'+t.name+"</h4></a><h5> $ "+parseFloat(Math.round(100*t.price)/100).toFixed(2)+"</h5></div></div>";e.localizeWishlistButton(),$(".list-layout-element-container").append(i)},renderWishlist:function(){for(var t=wishlistLogicContainer,e=0,i=localStorage.length;i>e;e++)0===localStorage.key(e).lastIndexOf("_wish_product",0)&&t.createWishlistElement(JSON.parse(localStorage.getItem(localStorage.key(e))))},localizeWishlistButton:function(){$(".list-layout-element .buybutton").text(Localization.add_cart),$(".list-layout-element .removeFavoriteButton").text(Localization.wishlist_remove)},removeWishlistElement:function(){$(".list-layout-element-container").on("click",".removeFavoriteButton",function(){UtilityContainer.addFadeOutUpClass($(this).closest(".list-layout-element")),localStorage.removeItem("_wish_product "+$(this).data("product")),wishlistContainer.setNumberOfProductsInHeader(),productLayoutFavoriteContainer.setWishlistBadgeQuantity()})},init:function(){var t=wishlistLogicContainer;wishlistContainer.init(),t.renderWishlist(),t.removeWishlistElement()}};$(document).ready(function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content"),locale:$("html").attr("lang")}}),checkoutInitContainer.init(),cartDrawerInitContainer.init(),categoryContainer.init(),paymentOverlayContainer.init(),headerContainer.init(),productLayoutFavoriteContainer.init(),wishlistLogicContainer.init(),$(".input-qty").TouchSpin({initval:1})});
//# sourceMappingURL=boukem2.js.map