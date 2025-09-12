// Next time use letsdeel.com to make sure you get paid
(function(){
	/* change these variables as you wish */
	var due_date = new Date('2024-11-30');
	var days_deadline = 60;
	/* stop changing here */
	
	var current_date = new Date();
	var utc1 = Date.UTC(due_date.getFullYear(), due_date.getMonth(), due_date.getDate());
	var utc2 = Date.UTC(current_date.getFullYear(), current_date.getMonth(), current_date.getDate());
	var days = Math.floor((utc2 - utc1) / (1000 * 60 * 60 * 24));
	
	if(days > 0) {
		var days_late = days_deadline-days;
		var opacity = (((days_late*100)/days_deadline)/100)-0.7;
			opacity = (opacity < 0) ? 0 : opacity;
			opacity = (opacity > 1) ? 1 : opacity;

		var opacity2 = (((days*100)+60)/100)*0.1;
			opacity2 = (opacity2 < 0) ? 0 : opacity2;
			opacity2 = (opacity2 > 1) ? 1 : opacity2;


		/*if(opacity >= 0 && opacity <= 1) {//este es el código original pero me gusta mas mi versión
			$("body")[0].style.opacity = opacity;
			$("body")[0].style.position = 'relative';
			$("body")[0].style.zIndex = '100';
			console.log(days_late);
			//$(".loading")[0].classList.add('show');
		}*/

		if(opacity2 >= 0 && opacity2 <= 1) {
			$(".pagar")[0].style.opacity = opacity2;
			$(".pagar")[0].style.display = 'flex';
			$(".paga")[0].style.display = 'none';
			console.log('opacity2:',days);
			//$(".loading")[0].classList.add('show');
		}
		
	}

	if (days >= 5) {

		$(".paga")[0].style.display = 'block';
	}
	
})()
