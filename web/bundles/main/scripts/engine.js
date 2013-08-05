$(function () {
	$('.fancybox').fancybox();

	$(".slideshow-standard").sliderkit({
		auto: true,
		autospeed: 1000,
		autostill: true,
		circular: true,
		timer: {
			fadeout: 0.5
		},
		panelfx: 'fancy',
		imagefx: {
			fxType: 'random'/*,
			fxDelay: 250,
			fxDuration: 5000*/
		}
	});

	$('.v-carousel').jcarousel({
		/*auto: 2,*/
		vertical: true,
		scroll: 2,
		wrap: 'circular'
	});

	if ($("div.add-comment").length > 0) {
		$("div.link-comment a").click(function () {
			if ($("div.add-comment").css("display") == "none") {
				$("div.add-comment").show("slow");
				$(this).html("Скрыть форму добавления отзыва");
			} else {
				$("div.add-comment").hide("slow");
				$(this).html("Добавить отзыв");
			}
		});
		CaptureSubmitComment();
		SetResetFormComment();
	}

	if ($('div.order-form').length > 0) {
		SetTimePicker();
		CaptureSubmitOrderForm();
		SetResetOrderForm();
	}
});


/* -------------------------------------------------------------------------------- */

function SetResetOrderForm () {
	$("div.order-form").find("input[type=button]").click(function () {
		if (confirm("Очистить поля формы?")) {
			$("div.order-form").find("input[type=text], select, textarea").each(function () {
				$(this).val("");
				$(this).removeClass("error-input");
			});
		}
	});
}

function CaptureSubmitOrderForm () {
	$("div.order-form").find("form").submit(function () {
		var is_empty = false;

		$("div.order-form").find(".obligat-field").each(function () {
			var val = $.trim($(this).val());
			if (val == "") {
				is_empty = true;
				$(this).addClass("error-input");
			}
		});

		if (is_empty) {
			return false;
		}

		return true;
	});
}

/* -------------------------------------------------------------------------------- */

function SetResetFormComment () {
	$("div.add-comment").find("input[type=button]").click(function () {
		$("div.add-comment").find("input[type=text], select, textarea").each(function () {
			$(this).val("");
			$(this).removeClass("error-input");
		});
	});
}

function CaptureSubmitComment () {
	$("div.add-comment").find("form").submit(function () {
		var is_empty = false;

		$("div.add-comment").find(".asterisk").each(function () {
			var val = $.trim($(this).val());
			if (val == "") {
				is_empty = true;
				$(this).addClass("error-input");
			}
		});

		if (is_empty) {
			return false;
		}

		return true;
	});
}

/* -------------------------------------------------------------------------------- */

function SetTimePicker () {
	$("input.datepicker").datepicker({
		monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
		monthNamesShort: ["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"],
		dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
		dayNamesShort: ["вос","пон","вто","сре","чет","пят","суб"],
		dayNamesMin: ["вс","пн","вт","ср","чт","пт","сб"],
		firstDay: 1,

		closeText: "установить",
		currentText: "сегодня",
		prevText: "предыдущий месяц",
		nextText: "следующий месяц",

		changeMonth: true,
		changeYear: true,

		yearRange: "c-65:c+1",

		dateFormat: 'dd.mm.yy'
	});

	$("input.datetimepicker").datetimepicker({
		monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
		monthNamesShort: ["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"],
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
		stepMinute: 10,

		minDate: (new Date())
	});
}

/* -------------------------------------------------------------------------------- */