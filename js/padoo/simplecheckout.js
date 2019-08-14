/*
 * Magento SimpleCheckout Extension
 *
 * @copyright:	PadoosoftTeam (http://www.padoosoft.com)
 * @version:	1.0
 *
 */

function startLoadingData(only_review_block){
	if(only_review_block){
		checkoutoverlay.createOverlay('review', $('onecheckout-review'));
	}else{
		if(update_shipping > 0)checkoutoverlay.createOverlay('shipping_methods', $('onecheckout_shipping_method'));
		if(update_payment > 0)checkoutoverlay.createOverlay('payment_methods', $('onecheckout_payment_method'));
		if(update_review > 0)checkoutoverlay.createOverlay('review', $('onecheckout-review'));
	}
}

function stopLoadingData(){
	checkoutoverlay.hideOverlay();
}


function shippingAddressChanged(){
	
	if(!$('billing_use_for_shipping_yes').checked){
		sendShippingAddress();
	}
}

function billingAddressChanged(){
		sendBillingAddress();
}

function changeShippingAddressMode(){
	
	$flag = this.checked;
		
	if($flag){
		$('shipping-address-form').style.display = 'none';
		sendBillingAddress();
	}else{
		$('shipping-address-form').style.display = 'block';
		sendShippingAddress();
	};
	
}

function buildQueryString(elements){
	
	var q = '';
	
	for(var i = 0;i < elements.length;i++){
		if((elements[i].type == 'checkbox' || elements[i].type == 'radio') && !elements[i].checked){
			continue;
		}
		q += elements[i].name + '=' + elements[i].value;
		
		if(i+1 < elements.length){
			q += '&';
		}
		
	}
	return q;
}
function updateShopping(container){
	startLoadingData();
	var editItemUrl = checkoutUpdateUrl;
	var q = prepairParams(container);
	updateFormData(editItemUrl, q);
}
function deleteShopping(id){
	startLoadingData();
	var url=checkoutDeleteUrl+'id/'+id;		
	updateFormData(url, '');
}
function prepairParams(container) {
	var params = {};
	var fields = $(container).select('input', 'select', 'textarea');
	var data = Form.serializeElements(fields, true);
	 data =  $H(data);
	 var query ="";
	if (data) {
	   data.each(function(value) {
			if(query !="")
				query +="&"
			query +=value[0] + "=" + value[1];
			
		});
	}
	return query;
}
function update_coupon(remove){
	if($('coupon_code').value == ""){
		alert('Please enter Coupon code !');
		return;
	}
	startLoadingData();
	if (remove){
		
		
        $('remove-coupone').value = "1";
		var q = buildQueryString($$('#coupon_code, #remove-coupone'));
	
		return updateFormData(checkoutCouponUrl, q);
	}
	else{
		
        $('remove-coupone').value = "0";
		var q = buildQueryString($$('#coupon_code, #remove-coupone'));
	
		return updateFormData(checkoutCouponUrl, q);
	}
}

function elogin(e, p, url){
	
	$('elogin-loading').style.display = 'block';
	$('elogin-buttons').style.display = 'none';
	
	var request = new Ajax.Request(url,
	  {
	    method:'post',
	    parameters:'username='+e+'&password='+p,
	    onSuccess: function(transport){ var response = eval('('+(transport.responseText || false)+')');
	      
	      if(response.error){
	      	  $('elogin-message').innerHTML = response.message;
	      	  $('elogin-loading').style.display = 'none';
			  $('elogin-buttons').style.display = 'block';
	      }else{
	      	  
	      	  location.reload();
	      	  
	      }
	      
	    },
	    onFailure: function(){ alert('Something went wrong...');stopLoadingData(); }
	  });
}

function updateFormData(url, q){
	
	var request = new Ajax.Request(url,
	  {
	    method:'post',
	    parameters:q,
	    onSuccess: function(transport){ var response = eval('('+(transport.responseText || false)+')');
	      
	      if(response.error){
			  if(response.review){
	      	  	$('onecheckout-review-info').update(response.review);
	      	  }
			  stopLoadingData();
			  alert(response.message);
	      	  //coming soon...
	      }else{
	      	  if(response.shipping_rates && update_shipping > 0){
	      	  	$('onecheckout-shippingmethod-available').update(response.shipping_rates);
	      	  }
	      	  if(response.payments && update_payment > 0){
	      	  	$('onecheckout-paymentmethod-available').update(response.payments);
	      	  }
	      	  if(response.review && update_review >0){
				  
				var data = String(response.review)
				if(data =="no_data"){
					window.location = defaultCart;
					return ;
				 }
	      	  	$('onecheckout-review-info').update(response.review);
	      	  }
			  
			  if(response.coupon){
	      	  	$('onecheckout-coupon').update(response.coupon);
	      	  }
			  if(response.shippingMethods){
				$('onecheckout_shipping_method').update(response.shippingMethods);
			  }
			  
			stopLoadingData();	
	      }
	      
	    },
	    onFailure: function(){ alert('Something went wrong...');stopLoadingData(); }
	  });
	
}


function sendBillingAddress(){
	
	startLoadingData();
	
	var q = buildQueryString($$('#onecheckout-addressbilling input, #onecheckout-addressbilling select, #onecheckout-addressbilling textarea, #billing_use_for_shipping_yes'));
	
	if($('billing_use_for_shipping_yes') && $('billing_use_for_shipping_yes').checked){
		return updateFormData(checkoutDefaultUrl, q);
	}
	
	return updateFormData(checkoutBillingUrl, q);
	
	
}

function sendShippingAddress(){
	
	startLoadingData();
	
	var q = buildQueryString($$('#shipping-address-form input, #shipping-address-form select, #shipping-address-form textarea'));
	
	return updateFormData(checkoutShippingUrl, q);
	
}

function sendMethods(){
	
	startLoadingData(true);
	
	var q = '';
	
	q += buildQueryString($$('#onecheckout-shippingmethod input, #onecheckout-shippingmethod select, #onecheckout-shippingmethod textarea'));
	q += '&';
	q += buildQueryString($$('#onecheckout-paymentmethod input, #onecheckout-paymentmethod select, #onecheckout-paymentmethod textarea'));
	
	return updateFormData(checkoutTotalsUrl, q);
	
}

var checkoutoverlay = {
	overlay:{},
	hideOverlay:function(){
		for(i in this.overlay){
			this.overlay[i].style.display = 'none';
		}
	},
	createOverlay:function(id, container){
		
		if(this.overlay['sln-overlay-'+id]){
		
			var overlay = this.overlay['sln-overlay-'+id];
		
		}else{
		
			var overlay = document.createElement('div');
			overlay.id = 'sln-overlay-'+id;
			var span = document.createElement('span');
			span.className = "img-load";
			overlay.appendChild(span);
			container.appendChild(overlay);
			
			this.overlay['sln-overlay-'+id] = overlay;
		}
		
		if(typeof SLN_IS_IE == 'boolean'){
			container.style.position = 'relative';
		}else{
			SLN_IS_IE = false;
		}
		
		overlay.style.top			= 0;
		overlay.style.left			= 0;
		overlay.style.width			= container.offsetWidth + (SLN_IS_IE ? 1 : 0) + 'px';	
		overlay.style.height		= container.offsetHeight + 'px';
		overlay.style.display 		= 'block';
		overlay.style.background	= '#ffffff';
		overlay.style.position		= 'absolute';
		overlay.style.opacity		= '0.7';
		overlay.style.filter		= 'alpha(opacity: 70)';
		
	}
}




var paymentForm = Class.create();
paymentForm.prototype = {
	beforeInitFunc:$H({}),
    afterInitFunc:$H({}),
    beforeValidateFunc:$H({}),
    afterValidateFunc:$H({}),
    initialize: function(formId){
        this.form = $(this.formId = formId);
    },
    init : function () {
        //var elements = Form.getElements(this.form);
        
        var elements = $$('#onecheckout-paymentmethod-available input, #onecheckout-paymentmethod-available select, #onecheckout-paymentmethod-available textarea');
        
        /*if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }*/
        var method = null;
        for (var i=0; i<elements.length; i++) {
            if (elements[i].name=='payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            }
            elements[i].setAttribute('autocomplete','off');
        }
        if (method) this.switchMethod(method);
    },
    
    switchMethod: function(method){
        if (this.currentMethod && $('payment_form_'+this.currentMethod)) {
        	
            var form = $('payment_form_'+this.currentMethod);
            form.style.display = 'none';
            var elements = form.getElementsByTagName('input');
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
            var elements = form.getElementsByTagName('select');
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
            

        }
        if ($('payment_form_'+method)){
            var form = $('payment_form_'+method);
            form.style.display = '';
            var elements = form.getElementsByTagName('input');
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            var elements = form.getElementsByTagName('select');
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            this.currentMethod = method;
        }
    }
}
var billing = Class.create();
billing = billing.prototype = {
	newAddress: function(isNew){
        if (isNew) {
        	
            $('billing-new-address-form').select('input[type=text], select, textarea').each(function(e){if(!e.getAttribute('disabled') && !e.getAttribute('readonly')){e.value = ''};});
            
            Element.show('billing-new-address-form');
        } else {
            Element.hide('billing-new-address-form');
        }
        
        billingAddressChanged();
    }
}
var shipping = Class.create();
shipping = billing.prototype = {
	newAddress: function(isNew){
        if (isNew) {
        	
            $('shipping-new-address-form').select('input[type=text], select, textarea').each(function(e){if(!e.getAttribute('disabled') && !e.getAttribute('readonly')){e.value = ''};});
            
            Element.show('shipping-new-address-form');
        } else {
            Element.hide('shipping-new-address-form');
        }
        
        shippingAddressChanged();
        
        //shipping.setSameAsBilling(false);
    }
}