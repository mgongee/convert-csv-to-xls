$(document).ready(function(){
	
	var current_price = document.getElementById("current_price");
	var stoploss_price = document.getElementById("stoploss_price");
	var stoploss_ticks = document.getElementById("stoploss_ticks");
	var takeprofit_price = document.getElementById("takeprofit_price");
	
	var currentprice_message_field = document.getElementById("currentprice_message_field");
	var takeprofit_message_field = document.getElementById("takeprofit_message_field");
	var stoploss_message_field = document.getElementById("stoploss_message_field");
	
	var message_text = document.getElementById("message");
	
	/*** Validate Inputs **/
	
	function validateCurrentPrice(message) {
		if (!checkCurrentPrice(current_price.value)) {
			message.innerHTML = "The price must be between 1.10 and 1.85"
			return false;
		}
		else {
			message.innerHTML = ""
			return true;
		}
	};
	
	function validateStopLoss(message) {
		if (!checkStopLossPrice(stoploss_price.value,current_price.value)) {
			message.innerHTML = "The Stop Loss price must be greater than Current Price"
			return false;
		}
		else {
			message.innerHTML = ""
			return true;
		}
	};

	
	function validateTakeProfit(message) {
		if (!checkTakeProfitPrice(takeprofit_price.value,current_price.value)) {
			message.innerHTML = "The Take Profit price must be less than Current Price and not negative"
			return false;
		}
		else {
			message.innerHTML = ""
			return true;
		}
	};
	
	current_price.oninput = function() { validateCurrentPrice(currentprice_message_field); };
	stoploss_price.oninput = function() { validateStopLoss(stoploss_message_field); };
	takeprofit_price.oninput = function() { validateTakeProfit(takeprofit_message_field); };
	
	
	/*** Check values **/
	
	function checkCurrentPrice(value) {
		if ((value >= 1.1) && (value <= 1.85)) return true;
		return false;
	}
	
	function checkStopLossPrice(stoplossPriceValue,currentPriceValue) {
		if (stoplossPriceValue > currentPriceValue) return true;
		return false;
	}
	
	function checkTakeProfitPrice(takeprofit_price_value,currentPriceValue) {
		if ((takeprofit_price_value < currentPriceValue) && (takeprofit_price_value > 0)) return true;
		return false;
	}
	
	/*** Calculate **/
	
	var diffTable = [
		{'start':1,'end':2,'pointspertick': 1},  // For values between 1.01 and 2.00 use 1 point difference = 1 tick
		{'start':2,'end':3,'pointspertick': 2},  // For values between 2.01 and 3.00 use 2 point difference = 1 tick (round up to the next number divisible by 2 where necessary)
		{'start':3,'end':4,'pointspertick': 5},  // For values between 3.01 and 4.00 use 5 point difference= 1 tick (round up to the next number divisible by 5 where necessary)
		{'start':4,'end':6,'pointspertick': 10}, // For values between 4.00 and 6.00 use 10 point difference = 1 tick (round up to the next number divisible by 10 where necessary)
		{'start':6,'end':10,'pointspertick': 20},// For values between 6.00 and 10.00 use 20 point difference = 1 tick (round up to the next number divisible by 20 where necessary)
		{'start':10,'end':20,'pointspertick': 50},
		{'start':20,'end':30,'pointspertick': 100},
		{'start':30,'end':50,'pointspertick': 200},
		{'start':50,'end':100,'pointspertick': 500},
		{'start':100,'end':1000,'pointspertick': 1000}
	];
	
    $('#calculate_bttn').click(function() {
		calculateTicks();
    });
	
	function calculateTicks() {
		if (validateCurrentPrice(currentprice_message_field) &&
			validateStopLoss(stoploss_message_field) &&
			validateTakeProfit(takeprofit_message_field))
			
		{
			valid = true;
		}
		else {
			valid = false;
		}
		
		if (parseFloat(stoploss_price.value) && validateStopLoss(stoploss_message_field)) {
			$('#stoploss_ticks').val(calculateStopLossTicks(stoploss_price.value,current_price.value));
		}
		else {
			stoploss_message_field.innerHTML = "The Stop Loss price must be a number"
			$('#stoploss_ticks').val("");
		}
		
		if (parseFloat(takeprofit_price.value) && validateTakeProfit(takeprofit_message_field))  {
			$('#takeprofit_ticks').val(calculateTakeProfitTicks(takeprofit_price.value,current_price.value));
		}
		else {
			takeprofit_message_field.innerHTML = "The Take Profit price must be a number"
			$('#takeprofit_ticks').val("");
		}

		if (!valid) {
			$('#calculate_bttn').fadeOut();
			$('#calculate_bttn').fadeIn();
		}
	}
	
	function calculateStopLossTicks(stoplossPriceValue,currentPriceValue) {
		ticksStartLevel = findTicksLevel(currentPriceValue);
		ticksEndLevel = findTicksLevel(stoplossPriceValue);
		
		if (ticksEndLevel === false) {
			return '?'
		}
		
		ticksCount = 0;
		
		ticksStartCount = findTicksCount(ticksStartLevel, currentPriceValue,'start');
		
		if (ticksStartLevel < ticksEndLevel) {
			for (ticksLevel = ticksStartLevel + 1; ticksLevel < ticksEndLevel; ticksLevel++) {
				points = 100 * (diffTable[ticksLevel].end - diffTable[ticksLevel].start)
				ticks = points / diffTable[ticksLevel].pointspertick;
				ticksCount += Math.ceil(ticks)
			}
			
			ticksEndCount = findTicksCount(ticksEndLevel, stoplossPriceValue,'end');
			
			ticks = ticksStartCount + ticksCount + ticksEndCount;
		}
		else if (ticksStartLevel + 1  == ticksEndLevel) {
			ticksEndCount = findTicksCount(ticksEndLevel, stoplossPriceValue,'end');
			ticks = ticksStartCount + ticksEndCount;
		}
		else {
			ticksEndCount = findTicksCount(ticksEndLevel, stoplossPriceValue,'start');
			ticks = ticksStartCount - ticksEndCount;
		}
		
		
		return Math.round(ticks);
	}
	
	
	function calculateTakeProfitTicks(takeprofitPriceValue,currentPriceValue) {

		ticksLevel = findTicksLevel(currentPriceValue);
		
		points = 100 * (currentPriceValue - takeprofitPriceValue)
		ticks = points / diffTable[ticksLevel].pointspertick;
		
		ticksCount = Math.round(ticks)
		return ticksCount
	}
			
	
	function findTicksLevel(value){
		for (var i = 0; i < diffTable.length; i++) {
			if ((value > diffTable[i].start) && (value <= diffTable[i].end)) {
				return i;
			}
		}
		return false;
	}
	
	function findTicksCount(ticksLevel, priceValue, mode) {
		if (mode == 'start') {
			difference = diffTable[ticksLevel].end - priceValue;
		}
		else {
			difference = priceValue - diffTable[ticksLevel].start;
		}
		
		points = 100 * difference;
		ticks =  points / diffTable[ticksLevel].pointspertick;
		
		return ticks;
	}
});
    