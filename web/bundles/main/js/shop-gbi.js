$(function () {
//	$(document).on("mouseenter", "#cartInner.cart-full", function(){
//		$(".mbasket-items").css("display", "block");
//	});
//	$(document).on("mouseleave", ".menu-basket", function(){
//		$(".mbasket-items").css("display", 'none');
//	});

	$("#order_phone,#price_list_booking_phone").mask("+9(999) 999-9999",{placeholder:"+_(___) ___-____"});

	$('#price_list_booking_city').kladr({
		type: $.kladr.type.city
	});

	$(document).on("mousedown", "#cartInner_full", function(){ 
        $("#cartInner_full").css("background-image", 'url("../images/shop_but_full_a.png")');
	});
	$(document).on("mousedown", "#cartInner_empty", function(){ 
        $("#cartInner_empty").css("background-image", 'url("../images/shop_but_empty_a.png")');
	});
	$(document).on("mousedown", ".calc_button", function(){ 
        $(".calc_button img").attr('src', '/images/calc_button_a.png');
	});
	
	$("#cartInner_new2").hover(
      function () {
        $(".mbasket-items").css("display", "block");
      },
      function () {
        $(".mbasket-items").css("display", "none");
	  }
	);
	var count_item = $("tr.cart-order").length;
	//$("#cartInner_new .basket-content").html("Выбрано позиций:&nbsp;"+count_item);

    /* TryHideBasket();

	$("div.basket").sticky({
		className: 'basket-sticky',
		wrapperClassName: 'basket-sticky-wrapper'
	});*/

//	$('input#phone').mask('(999) 999-99-99');

    /*
	$("input.basket-rest").each(function () {
		$.spin.imageBasePath = "/assets/templates/default/images/spin/";
		$(this).spin({
			min: 1,
			max: 10000
		});
	});*/

	// --------------------------------------------------------------------------------

//	if ($("a.add-basket").length > 0) {
//		$("a.add-basket").click(AddGoodsToBasket);
//	}
//
//	// --------------------------------------------------------------------------------
//
//	if ($("a.order-delete").length > 0) {
//		EngineDeleteOrderItem();
//	}
//
//	if ($("a.clear-basket").length > 0) {
//		$("a.clear-basket").click(ClearBasket);
//	}

	// --------------------------------------------------------------------------------

	if ($("div.form-order").length > 0) {
		EngineSpinOrder();
		EngineOrder();
	}
});

function EngineSpinOrder () {
	$.spin.imageBasePath = "/assets/templates/default/images/spin/";

	$("input.order-rest").each(function () {
		/*$(this).spin({
			min: 1,
			max: 10000,
			changed: function (n, o) {
				RecalcOrderContent();
			}
		});*/

		$(this).change(function () {
			var rest = parseInt($.trim($(this).val()));
			if (isNaN(rest) || rest == 0) {
				rest = 1;
				$(this).val(rest);
			}

			RecalcOrderContent();
		});
	});
}

function RecalcOrderContent () {
	var arr_order = [];
	var $order = $("div.order-basket div.gbi-list");
	var cnt_goods = 0;

	$order.find("input.order-rest").each(function () {
		var $parent_row = $(this).parent().parent();

		var code = $parent_row.find("td.code").text();
		var rest = parseInt($.trim($(this).val()));
		if (isNaN(rest) || rest == 0) {
			rest = 1;
			$(this).val(rest);
		}

		cnt_goods += rest;

		arr_order.push(code + ":" + rest);
	});

	var $cell_cnt_goods = $order.find("th.cnt-goods");
	$cell_cnt_goods.text(cnt_goods);

	var order_basket = arr_order.join("-");

	if (arr_order.length == 0) {
		ClearBasket(false);
		return false;
	}

	$.ajax({
		type: "GET",
		url: "/basket/",
		data: {
			mode: "edit",
			order_basket: order_basket
		},
		cache: false,
		success: RecalcBasketContent
	});

	$("div.self-order").text(order_basket);
}

function EngineDeleteOrderItem () {
	$("a.order-delete").click(function () {
		var $parent_row = $(this).parent().parent();
		$parent_row.remove();
		RecalcOrderContent();
	});
}

function AddGoodsToBasket () {
	if ($(this).hasClass("disabled")) {
		return false;
	}

	var category = $(this).attr("category");
	var code = $(this).attr("code");
	var rest = parseInt($.trim($("input.basket-rest[code=" + code + "]").val()));
	if (isNaN(rest) || rest == 0) {
		rest = 1;
	}



	$.ajax({
		type: "GET",
		url: "/basket/",
		data: {
			mode: "add",
			code: code,
			rest: rest
		},
		cache: false,
		success: function (data) {
			RecalcBasketContent(data);

			$("a.add-basket").each(function () {
				$(this).removeClass("disabled");
				$(this).html('<i class="icon-shopping-cart" style="margin-right: 7px;"></i>В корзину');
			});

			$("input.basket-rest").each(function () {
				$(this).val("1");
			});
		}
	});

	return false;
}

function RecalcBasketContent (data) {
	console.log('data', data);
	$("div.basket-content").html(data);
    var $basket_img = $("div.menu-basket-img");
    var html = '<img src="/bundles/main/images/busket-full.png">';
    $basket_img.html(html);
}

function ClearBasket (from_basket) {
	var is_from_basket = false;
	if ($(this).hasClass("from-basket")) {
		is_from_basket = true;
	} else {
		if (from_basket == true) {
			is_from_basket = true;
		}
	}

	$.ajax({
		type: "GET",
		url: "/basket/",
		data: {
			mode: "clear"
		},
		cache: false,
		success: function (data) {
			$("div.basket-content").html('0');
            var $basket_img = $("div.menu-basket-img");
            var html = '<img src="/bundles/main/images/basket-empty.png">';
            $basket_img.html(html);
			var html = ' ';
			$("div.cart-actions").html(html);
			if (!is_from_basket) {
				location.href = "/";
			}
		}
	});

	return false;
}

// --------------------------------------------------------------------------------

function EngineOrder () {
	var $form = $("div.form-order form");
	$form.submit(OrderFormSumbit);

	$form.find("input[type=button]").click(OrderFormReset);
}

function OrderFormSumbit () {
	$("div.form-order td.warning").empty();

	$(this).find("input, select, textarea").each(function () {
		$(this).removeClass("error-input");
	});

	DisabledButtons(true);

	var is_error = false;
	$(this).find("input, select, textarea").each(function () {
		var type = $(this).attr("type") + "";
		if (type != "" && type != "hidden" && type != "submit" && type != "reset" && type != "button") {

			var $parent_row = $(this).parent().parent();
			if ($parent_row.find("span.obligat").length > 0) {
				var val = $.trim($(this).val());
				if (val == "") {
					$(this).addClass("error-input");
					is_error = true;
				}
			}
		}
	});

	var email_val = $.trim($("input#order_email").val());
	if (!isValidEmailAddress(email_val)) {
		$("input#email").addClass("error-input");
		is_error = true;
	}

    /*
	var phone_val = $.trim($("input#phone").val()) + "";
	if (phone_val.length != 15) {
		$("input#phone").addClass("error-input");
		is_error = true;
	}*/

	if (is_error) {
		var $div = $("div.form-order td.warning");
		$div.html("<div>Не все обязательные поля формы заполнены или заполнены неверно.</div>");

		DisabledButtons(false);
		return false;
	}

	return true;
}

function OrderFormReset () {
	var $form = $("div.form-order form");
	$form.find("input, select, textarea").each(function () {
		var type = $(this).attr("type") + "";
		if (type != "" && type != "hidden" && type != "submit" && type != "reset" && type != "button") {
			$(this).removeClass("error-input");
			$(this).val("");
		}
	});

	$("div.form-order td.warning").empty();

	DisabledButtons(false);
}

function DisabledButtons (is_disabled) {
	var $form = $("div.form-order form");

	var $btn_submit = $($form.find("input[type=submit]").get(0));
	var $btn_reset = $($form.find("input[type=button]").get(0));

	if (is_disabled) {
		$btn_submit.attr("disabled", "disabled");
		$btn_reset.attr("disabled", "disabled");
	} else {
		$btn_submit.removeAttr("disabled");
		$btn_reset.removeAttr("disabled");
	}
}

function isValidEmailAddress (emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}

function spinUpCount(el) {
	var currentVal = parseInt($(el).parent().find('.basket-rest').val());
	$(el).parent().find('.basket-rest').val(currentVal + 1);
}

function spinDownCount(el) {
	var currentVal = parseInt($(el).parent().find('.basket-rest').val());
	if(currentVal > 1) {
		$(el).parent().find('.basket-rest').val(currentVal - 1);
	}
}