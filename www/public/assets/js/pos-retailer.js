if (document.readyStatus == 'loading') {
	document.addEventListener('DOMContentLoaded', ready);
}else{
	ready();
}

function ready(){
	try{
		requestAvailableStock();

		var addToCartButton = document.getElementsByClassName('add-to-cart-button')[0];
		addToCartButton.addEventListener('click', selectCartItem);

		var processTransctionButton = document.getElementsByClassName('btn-process-transaction')[0];
		processTransctionButton.addEventListener('click', processTransction);
	} catch(error) {
		console.error(error.message);
	}
}

function processTransction(event){
	try{
	    event.preventDefault();
	    var cartItems = document.getElementsByClassName('cart-items')[0];
	    var cartRows = cartItems.getElementsByClassName('cart-row');
	    if (typeof cartRows !== "undefined" && cartRows !== null && cartRows.length !== null && cartRows.length > 0) {
			$("#pageloader").fadeIn(100);
		    var cart = new Array();
		    for (var i = 0; i < cartRows.length; i++) {
				var cartRow = cartRows[i];
				var id = cartRow.getElementsByClassName('remove-item')[0].value;
				var qty = parseInt(cartRow.getElementsByClassName('cart-quantity-input')[0].value);
				var unitPrice = parseFloat(cartRow.getElementsByClassName('cart-price')[0].innerText.replace('Tshs ', ''));
				cart[i] = new Array(id, qty, unitPrice);
		    }
		    jQuery.ajax({
				url: "?url=home/process_payments",
				type: "POST",
				data: {cart:cart},
				dataType: "JSON",
	      		timeout: 50000,
				success:function(data){
					$("#pageloader").fadeOut("slow");
					if (data.response == "error") {
						toastr.error(data.message);
					}
					if (data.response == "warning") {
						toastr.warning(data.message);
					}
					if (data.response == "success") {
						while(cartItems.hasChildNodes()){
							cartItems.removeChild(cartItems.firstChild);
						}
						requestAvailableStock();
						updateCartTotal();
						toastr.success(data.message);
					}
				},
				error:function (error){
					console.log(error.responseText)
					$("#pageloader").fadeOut("slow");
					toastr.error('Unable to process the request.');
				}
		    });
		}
	} catch(error) {
		console.error(error.message);
	}
}

function removeCartItem(event){
	try{
		var buttonClicked = event.target;
		var elementToRemove = buttonClicked.parentElement.parentElement;
		elementToRemove.remove();
		checkInputsValidity();
	} catch(error) {
		console.error(error.message);
	}
}

function quantityChanged(event){
	try{
		var input = event.target;
		if (isNaN(input.value) || parseInt(input.value) <= 0) {
			input.value = 1;
		}
		var cartItem = input.parentElement.parentElement;
		var availableQty = cartItem.getElementsByClassName('available-quantity-in-store')[0].innerText;
		if (parseInt(input.value) > parseInt(availableQty)) {
			input.classList.add('is-invalid');
			toastr.info('The quantity you want to sell has exceeded the available quantity in stock.!');
			input.value = 1;
		}
		if (parseInt(input.value) > 0 && parseInt(input.value) <= parseInt(availableQty)) {
			input.classList.remove('is-invalid');
			input.classList.add('is-valid');
		}
		checkInputsValidity();
	} catch(error) {
		console.error(error.message);
	}
}

function selectCartItem(event){
	try{
		var button = event.target;
		var cartItem = button.parentElement.parentElement;
		var selectElement = cartItem.getElementsByClassName('select-item')[0];
		var drugDetails = selectElement.value;
		if (typeof drugDetails !== "undefined" && drugDetails !== null && drugDetails.length !== null && drugDetails.length > 0){
			addItemToCart(drugDetails);
			updateCartTotal();
		}
	} catch(error) {
		console.error(error.message);
	}
}

function checkInputsValidity(){
	try{
		var processTransactionButton = document.getElementsByClassName('btn-process-transaction')[0];
		var cartItemsContainer = document.getElementsByClassName('cart-items')[0];
		var cartRows = cartItemsContainer.getElementsByClassName('cart-row');
		var countValidInput = 0;
		for (var i = 0; i < cartRows.length; i++) {
			var cartRow = cartRows[i];
			var availableQty = cartRow.getElementsByClassName('available-quantity-in-store')[0];
			var cartQty = cartRow.getElementsByClassName('cart-quantity-input')[0];
			if (parseInt(cartQty.value) > parseInt(availableQty.innerText)) {
				processTransactionButton.setAttribute("disabled", "");
			}else{
				countValidInput++;
			}
		}
		if (cartRows.length == countValidInput) {
			processTransactionButton.removeAttribute("disabled");
			updateCartTotal();
		}
	} catch(error) {
		console.error(error.message);
	}
}

function addItemToCart(drugDetails){
	try{
		drugDetails = drugDetails.split(",", 7); //Create an array from the POST string
		var cartRow = document.createElement('tr');
		cartRow.classList.add('cart-row');
		var cartRowContents = '<td class="cart-item-brand-name">'+drugDetails[1]+'</td><td class="cart-item-generic-name">'+drugDetails[2]+'</td><td><span class="cart-price">'+'Tshs '+drugDetails[3]+'</span></td><td class="available-quantity-in-store">'+drugDetails[4]+'</td><td><input type="number" class="cart-quantity-input col-6 form-control" name="qty" value="'+drugDetails[5]+'" required></td><td class="cart-sub-total-price">'+'Tshs '+drugDetails[6]+'</td><td><button type="button" value="'+drugDetails[0]+'" class="btn btn-danger btn-sm remove-item">Remove</button></td>';
		cartRow.innerHTML = cartRowContents;
		var cartItems = document.getElementsByClassName('cart-items')[0];
		var cartItemNames = cartItems.getElementsByClassName('cart-item-brand-name');
		for (var i = 0; i < cartItemNames.length; i++){
			if (cartItemNames[i].innerText == drugDetails[1]) {
				toastr.info('Item is Already Added to Cart!');
				return;
			}
		}
		cartItems.append(cartRow);
		cartRow.getElementsByClassName('cart-quantity-input')[0].addEventListener('input', quantityChanged);
		cartRow.getElementsByClassName('remove-item')[0].addEventListener('click', removeCartItem);
	} catch(error) {
		console.error(error.message);
	}
}

function updateCartTotal(){
	try{
		var cartItemContainer = document.getElementsByClassName('cart-items')[0];
		var cartRows = cartItemContainer.getElementsByClassName('cart-row');
		var total = 0;
		for (var i = 0; i < cartRows.length; i++) {
			var cartRow = cartRows[i];
			var priceElement = cartRow.getElementsByClassName('cart-price')[0];
			var price = parseFloat(priceElement.innerText.replace('Tshs ', ''));
			var quantityElement = cartRow.getElementsByClassName('cart-quantity-input')[0];
			var quantity = parseFloat(quantityElement.value);
			var sub_total = (price * quantity);
			sub_total = Math.round(sub_total * 100) / 100; //Round to 2 decimal places
			cartRow.getElementsByClassName('cart-sub-total-price')[0].innerText = 'Tshs ' + sub_total + '/=';
			total = total + (price * quantity);
		}
		total = Math.round(total * 100) / 100; //Round to 2 decimal places
		document.getElementsByClassName('cart-total-price')[0].innerText = 'Tshs ' + total + '/=';
	} catch(error) {
		console.error(error.message);
	}
}

function requestAvailableStock(){
	try {
		var request = new XMLHttpRequest();
		request.open("POST", "?url=home/retrieve_available_stock");
		request.responseType = 'text';
		request.timeout = 5000; // 5 seconds
		request.ontimeout = function() {
			console.error('Request Timeout!!! (' + this.responseURL + ')');
		};
		request.onreadystatechange = function() {
			if(this.readyState === 0){
				console.log('Initializing Request');
			}
			if(this.readyState === 1){
				console.log('Request Started.');
			}
			if(this.readyState === 2){
				console.log('Headers Received.');
			}
			if(this.readyState === 3){
				console.log('Loading Data...');
			}
	        if(this.readyState === 4 && this.status === 200) {
	        	console.log('Request Completed! (' + this.responseURL + ').');
	            var drugsObj = JSON.parse(this.responseText);
	            var drugs = drugsObj["drug_list"];
	            if (typeof drugs !== "undefined" && drugs !== null) {
	            	var selectOptions = '<option value="" selected="selected" disabled>Search by Drug Name</option>\n';
	            	for (var i = 0; i < drugs.length; i++) {
		            	selectOptions = selectOptions + '<option value="'+drugs[i]["drug_id"]+','+drugs[i]["brand_name"]+','+drugs[i]["generic_name"]+','+drugs[i]["unit_s_price"]+','+drugs[i]["qty"]+',1,'+drugs[i]["unit_s_price"]+'">'+drugs[i]["brand_name"]+'('+drugs[i]["generic_name"]+')</option>\n';
		            }
		            document.getElementById("select2-drug-list").innerHTML = selectOptions;
	            }
	        }
	        if(this.status === 404){
	        	console.error('Unable to retrieve stock list!!!.');
	        }
	        if(this.status === 503){
	        	console.error('Server is currently unavailable!!!.');
	        }
	    };
	    request.onprogress = function(event) {
	    	if(event.lengthComputable){
	    		console.log('Received ' + event.loaded + ' bytes of ' + event.total + ' bytes.');
	    	}else{
	    		console.log('Received ' + event.loaded + ' bytes.');
	    	}
	    };
	    request.onerror = function() {
	    	console.error('Request Failed!!! (' + this.responseURL + ').');
	    };
	    request.send();
	} catch(error) {
		console.error(error.message);
	}
}