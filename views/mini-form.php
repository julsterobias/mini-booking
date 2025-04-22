<div class="minibookcalc-container--main">
	<div class="minibookcalc--form">
		<div>
			<label><?php esc_html_e('Name', 'minibookcalc'); ?>
				<input type="text" id="mini-booking-name" placeholder="<?php esc_html_e('Your Name', 'minibookcalc'); ?>">
			</label>
		</div>
		<div>
			<label><?php esc_html_e('Address', 'minibookcalc'); ?>
				<input type="text" id="mini-booking-address" placeholder="<?php esc_html_e('Your Full Address', 'minibookcalc'); ?>">
			</label>
		</div>
		<div>
			<label><?php esc_html_e('Distance', 'minibookcalc'); ?>
				<input type="number" id="mini-booking-distance" placeholder="<?php esc_html_e('in Kilometer(Km)', 'minibookcalc'); ?>">
			</label>
		</div>
		<div>
			<label><?php esc_html_e('Number of rooms', 'minibookcalc'); ?>
				<select id="mini-number-rooms">
					<option value=""><?php esc_html_e('Select Option','minibookcalc'); ?></option>
					<?php 
					if (isset($data['rooms'])): 
						for ($x = 1; $x <= $data['rooms']; $x++):
							$room_text = ($x > 1)? 'Rooms' : 'Room';
					?>
						<option value="<?php echo esc_attr($x); ?>"><?php echo sprintf("%d %s",$x,$room_text); ?></option>
					<?php 
						endfor;
					endif; ?>
				</select>
			</label>
		</div>
		<div>
			<input id="mini-distance-rate" type="hidden" value="<?php echo esc_attr($data['rates']['distance']); ?>">
			<input id="mini-base-rate" type="hidden" value="<?php echo esc_attr($data['rates']['base']); ?>">
			<input id="mini-room-rate" type="hidden" value="<?php echo esc_attr($data['rates']['room']); ?>">
			<input id="mini-currency" type="hidden" value="<?php echo esc_attr($data['rates']['currency']); ?>">
			<div id="mini-total-label"><?php echo esc_html_e('Thanks {name}! Your estimated booking cost is', 'minibookcalc'); ?><span></span><i id="mini-show-calc"><?php esc_html_e('Show Calculation','minibookcalc'); ?></i></div>
			<div id="mini-total-calc">Base: <?php echo esc_html($data['rates']['currency'].$data['rates']['base']); ?> + &lpar; {distance} * <?php echo esc_html($data['rates']['currency'].$data['rates']['distance']); ?>  &rpar; + &lpar; {rooms} * <?php echo esc_html($data['rates']['currency'].$data['rates']['room']); ?>  &rpar;</div>
			<button class="mini-primary-button" id="mini-calc-button"><?php esc_html_e('Calculate', 'minibookcalc'); ?></button>
			<button class="mini-secondary-button" id="send-calc-button"><?php esc_html_e('Send', 'minibookcalc'); ?></button>
		</div>
	</div>
</div>