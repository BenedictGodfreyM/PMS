if (document.readyStatus == 'loading') {
	document.addEventListener('DOMContentLoaded', ready);
}else{
	ready();
}

function ready(){
    try{
        var stockItemsContainer = document.getElementsByClassName('stock-items')[0];
        if (typeof stockItemsContainer !== "undefined") {
            stockItemsContainer.addEventListener('mouseover', quantityChanged);
            var stockRows = stockItemsContainer.getElementsByClassName('stock-item');
            for (var i = 0; i < stockRows.length; i++) {
                var stockRow = stockRows[i];
                stockRow.getElementsByClassName('stock-expiry-date')[0].addEventListener('focusout', verifyExpiryDate);
                stockRow.getElementsByClassName('stock-qty')[0].addEventListener('input', quantityChanged);
                stockRow.getElementsByClassName('stock-price')[0].addEventListener('input', quantityChanged);
                stockRow.getElementsByClassName('stock-total-price')[0].addEventListener('input', quantityChanged);
            }
        }
    } catch(error) {
        console.error(error.message);
    }
}

function quantityChanged(event){
	try{
		var input = event.target;
		if (isNaN(input.value) || parseInt(input.value) <= 0) {
			input.value = null;
		}
        var stockItem = input.parentElement.parentElement;
        var sellingPrice = stockItem.getElementsByClassName('selling_price')[0];
        var stockPrice = stockItem.getElementsByClassName('stock-price')[0];
        if(typeof stockPrice.value !== "undefined" && (isNaN(stockPrice.value) == false) && stockPrice.value !== null){
            if (parseInt(stockPrice.value) > parseInt(sellingPrice.value)) {
                stockPrice.classList.add('is-invalid');
                $('#modal-body-data').html('<p>Error! The provided Unit Purchasing Cost of this Drug is greater than the existing Selling Price of the Drug.<br/><br/> The existing Selling Price of the Drug is Tshs ' + sellingPrice.value + '/=.</p>');
                $("#alert-modal").modal();
                stockPrice.value = null;
                stockPrice.focus();
            }
            if (parseInt(stockPrice.value) > 0 && parseInt(stockPrice.value) <= parseInt(sellingPrice.value)) {
                stockPrice.classList.remove('is-invalid');
                stockPrice.classList.add('is-valid');
            }
            updateTotal();
        }
	} catch(error) {
		console.error(error.message);
	}
}

function verifyExpiryDate(event){
    try{
        var expiryDateInput = event.target;
        var expiryDate = new Date(expiryDateInput.value);
        var today = new Date();
        if(expiryDate.getFullYear() < today.getFullYear()){
            notifyExpiryDate(expiryDateInput, true);
        }else if(expiryDate.getFullYear() === today.getFullYear()){
            if(expiryDate.getMonth() < today.getMonth()){
                notifyExpiryDate(expiryDateInput, true);
            }else if(expiryDate.getMonth() === today.getMonth()){
                if(expiryDate.getDate() < today.getDate()){
                    notifyExpiryDate(expiryDateInput, true);
                }else{
                    notifyExpiryDate(expiryDateInput, false);
                }
            }else{
                notifyExpiryDate(expiryDateInput, false);
            }
        }else{
            notifyExpiryDate(expiryDateInput, false);
        }
    } catch(error) {
        console.error(error.message);
    }
}

function notifyExpiryDate(expiryDateInput, isExpired){
    try{
        if(isExpired === true){
            expiryDateInput.classList.add('is-invalid');
            $('#modal-body-data').html('<p>Invalid Expiry Date!!!. Please verify if it is a common mistake or that the drug being added to stock has already expired.</p>');
            $("#alert-modal").modal();
        }else{
            expiryDateInput.classList.remove('is-invalid');
            expiryDateInput.classList.add('is-valid');
        }
    } catch(error) {
        console.error(error.message);
    }
}

function updateTotal(){
	try{
		var stockItemsContainer = document.getElementsByClassName('stock-items')[0];
		var stockRows = stockItemsContainer.getElementsByClassName('stock-item');
		var total = 0;
		for (var i = 0; i < stockRows.length; i++) {
			var stockRow = stockRows[i];
			var priceElement = stockRow.getElementsByClassName('stock-price')[0];
			var price = parseFloat(priceElement.value);
			var quantityElement = stockRow.getElementsByClassName('stock-qty')[0];
			var quantity = parseFloat(quantityElement.value);
			var sub_total = (price * quantity);
            sub_total = Math.round(sub_total * 100) / 100; //Round to 2 decimal places
            if (typeof sub_total !== "undefined" && sub_total !== null && sub_total > 0) {
                stockRow.getElementsByClassName('stock-total-price')[0].value = sub_total;
            }
			total = total + (price * quantity);
		}
		total = Math.round(total * 100) / 100; //Round to 2 decimal places
        if (typeof total !== "undefined" && total !== null && total > 0) {
            document.getElementsByClassName('stock-grand-total-price')[0].innerText = 'Tshs ' + total + '/=';
        }
	} catch(error) {
		console.error(error.message);
	}
}