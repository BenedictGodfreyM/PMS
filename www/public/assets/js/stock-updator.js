if (document.readyStatus == 'loading') {
	document.addEventListener('DOMContentLoaded', ready);
}else{
	ready();
}

function ready(){
    try{
        var inputQtyBought = document.getElementsByClassName('stock-qty')[0];
        var inputUnitCost = document.getElementsByClassName('stock-price')[0];
        var inputTotalCost = document.getElementsByClassName('stock-total-price')[0];
        if (typeof inputQtyBought !== "undefined" && typeof inputUnitCost !== "undefined" && typeof inputTotalCost !== "undefined") {
            inputQtyBought.addEventListener('input', updateTotal);
            inputUnitCost.addEventListener('input', updateTotal);
            inputTotalCost.addEventListener('input', updateTotal);
        }
    } catch(error) {
        console.error(error.message);
    }
}

function updateTotal(){
	try{
        var priceElement = document.getElementsByClassName('stock-price')[0];
        var price = parseFloat(priceElement.value);
        var quantityElement = document.getElementsByClassName('stock-qty')[0];
        var quantity = parseFloat(quantityElement.value);
        var total_cost = (price * quantity);
        total_cost = Math.round(total_cost * 100) / 100; //Round to 2 decimal places
        if (typeof total_cost !== "undefined" && total_cost !== null && total_cost > 0) {
            document.getElementsByClassName('stock-total-price')[0].value = total_cost;
        }
	} catch(error) {
		console.error(error.message);
	}
}