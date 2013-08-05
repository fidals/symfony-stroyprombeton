$(function () {
	$.getScript("/assets/templates/default/scripts/jquery.csspngfix.src.js");

	if ($("div.images").length > 0) {
		$("div.images-left a").hover(NavMove, NavStop);
		$("div.images-right a").hover(NavMove, NavStop);
	}

	if ($("div.order.form").length > 0) {
		SelectCar();
		SetTimePicker();
		CaptureSubmit();
		SetResetForm();
	}
});

/* -------------------------------------------------------------------------------- */

function SetResetForm () {
	$("div.order.form").find("input[type=button]").click(function () {
		$("div.order.form").find("input[type=text], select, textarea").each(function () {
			$(this).val("");
			$(this).removeClass("error-input");
		});
	});
}

function CaptureSubmit () {
	$("div.order.form").find("form").submit(function () {
		var is_empty = false;

		$("div.order.form").find(".asterisk").each(function () {
			var val = $.trim($(this).val());
			if (val == "") {
				is_empty = true;
				$(this).addClass("error-input");
			}
		});

		var date_begin = PrepareDate($("div.order.form").find("input[name=date_begin]"));
		var date_end = PrepareDate($("div.order.form").find("input[name=date_end]"));
		if (date_begin != null && date_end != null && date_begin >= date_end) {
			is_empty = true;
			alert("Дата и время подачи машины не могут быть больше даты и времени окончания поездки.");
		}

		if (is_empty) {
			return false;
		}

		return true;
	});
}

function PrepareDate ($inp) {
	var val = $.trim($inp.val());
	if (val == "") {
		return null;
	}

	var arr_dt = val.split(" ");
	var arr_d = arr_dt[0].split(".");
	var arr_t = arr_dt[1].split(":");

	return (new Date(parseInt(arr_d[2], 10),
					parseInt(arr_d[1], 10) - 1,
					parseInt(arr_d[0], 10),
					parseInt(arr_t[1], 10),
					parseInt(arr_t[0], 10),
					0,
					0));
}

function SetTimePicker () {
	$("input.datepicker").datetimepicker({
		monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
		dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
		dayNamesShort: ["вос","пон","вто","сре","чет","пят","суб"],
		dayNamesMin: ["вс","пн","вт","ср","чт","пт","сб"],
		firstDay: 1,

		closeText: "установить",
		currentText: "сегодня",
		prevText: "предыдущий месяц",
		nextText: "следующий месяц",

		ampm: false,
		dateFormat: 'dd.mm.yy',
		timeFormat: 'hh:mm',
		stepMinute: 5,

		minDate: (new Date())
	});
}

function SelectCar () {
	var url = location.search;
	var what = "car=";
	var ind = url.indexOf(what);
	if (ind == -1) {
		return;
	}

	var carid = url.substring(ind + what.length);
	ind = url.indexOf("&");
	if (ind != -1) {
		carid = carid.substring(0, ind);
	}
	carid = parseInt(carid);

	$("div.order.form").find("select[name=car] option[id=car_" + carid + "]").attr("selected", "selected");
}

function NavMove () {
	var direct = $(this).parent().hasClass("images-left") ? false : true;

	_nav_content_width = 0;
	var $child = $("div.images-content").children();
	for (var i = 0; i < $child.length; i++) {
		_nav_content_width += $($child.get(0)).innerWidth();
	}
	_nav_content_width -= parseInt($("div.images-content").parent().css("padding-right"), 10) * 2;

	if (direct) {
		NavAnimateToLeft();
	} else {
		NavAnimateToRight();
	}
}

function NavAnimateToRight () {
	var ml = parseInt($("div.images-content").css("margin-left"), 10);
	if (ml >= 0) {
		$("div.images-content").css("margin-left", "0px");
		return false;
	}

	$("div.images-content").animate({
		marginLeft: "+=10px"
	}, 20, 'linear', NavAnimateToRight);
}

function NavAnimateToLeft () {
	var $container = $("div.images-content").parent();
	var $content = $("div.images-content");

	var container_width = $container.width();
	var ml = -parseInt($content.css("margin-left"), 10);

	if (ml >= (_nav_content_width - container_width)) {
		return;
	}

	$content.animate({
		marginLeft: "-=10px"
	}, 20, 'linear', NavAnimateToLeft);
}

function NavStop () {
	var $div_content = $("div.images-content");
	$div_content.stop();
}

/* -------------------------------------------------------------------------------- */
