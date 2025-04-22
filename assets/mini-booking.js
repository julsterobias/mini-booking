document.addEventListener('DOMContentLoaded', function () {

	const calc_event = document.getElementById('mini-calc-button');
	calc_event.addEventListener('click', () => {
		do_calculations();

		const show_calc_info = document.getElementById('mini-show-calc');
		show_calc_info.addEventListener('click', () =>  {
			document.getElementById('mini-total-calc').style.display = 'block';
		});
	});

	const send_mini_booking_calc = document.getElementById('send-calc-button');
	send_mini_booking_calc.addEventListener('click', () => {
		send_booking_to_admin(send_mini_booking_calc);
	});

	const do_calculations = () => {

		if ( !mini_validate_form() ) return;

		const name				= document.getElementById('mini-booking-name').value;
		let distance			= document.getElementById('mini-booking-distance').value;
		distance = (distance)? parseFloat(distance): 0;
		let rooms				= document.getElementById('mini-number-rooms').value;

		rooms = (rooms)? parseInt(rooms) : 0;
		const distance_rate 	= parseInt(document.getElementById('mini-distance-rate').value);
		const base_rate 		= parseInt(document.getElementById('mini-base-rate').value);
		const room_rate 		= parseInt(document.getElementById('mini-room-rate').value);
		const currency			= document.getElementById('mini-currency').value;

		let total = 0;
		let distance_total 	= distance * distance_rate;
		let room_total	   	= rooms * room_rate;
		total = base_rate + distance_total + room_total;

		const total_label   = document.getElementById('mini-total-label');

		let get_message 	= total_label.innerHTML;
		get_message 		= get_message.replace("{name}", name);
		total_label.innerHTML = get_message;

		const span 			= total_label.querySelector('span');
		span.innerText 		= ' ' + currency + total;

		total_label.style.display = 'block';

		const calc_div 		= document.getElementById('mini-total-calc');
		calc_div_text		= calc_div.innerText;
		calc_div_text 		= calc_div_text.replace('{distance}', distance);
		calc_div_text 		= calc_div_text.replace('{rooms}', rooms);
		calc_div.innerText  = calc_div_text;

		document.getElementById('send-calc-button').style.display = 'inline-block';
		
	}

	const mini_validate_form = () => {
		const required_fields = ['mini-booking-name', 'mini-booking-distance', 'mini-number-rooms'];
		invalid = true;
		for (req of required_fields) {
			if (!document.getElementById(req).value) {
				document.getElementById(req).classList.add('invalid');
				invalid = false;
			} else {
				document.getElementById(req).classList.remove('invalid');
			}
		}
		return invalid;
	}

	const send_booking_to_admin = (btn) => {

		btn.disabled = true;

		const formData = new FormData();
		formData.append('action', 'send_mini_bookin_calc'); 
		formData.append('nonce', minibooking.nonce);
		formData.append('payload', JSON.stringify(
			{
				name: document.getElementById('mini-booking-name').value,
				address: document.getElementById('mini-booking-address').value,
				distance: document.getElementById('mini-booking-distance').value,
				number_of_rooms: document.getElementById('mini-number-rooms').value
			}
		));

		fetch(minibooking.ajaxurl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin' // for cookies, required if user is logged in
		})
		.then(response => response.json())
		.then(data => {
			alert(data.message);
			btn.disabled = false;
		})
		.catch(error => {
			console.error('Error:', error);
			btn.disabled = false;
		});
	}

});