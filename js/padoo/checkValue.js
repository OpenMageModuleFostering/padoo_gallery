function checkValue(el){
	for (var k=1; k<9; k++){
		if((el.value == $("onepagecheckout_positions_position"+k).value) && (el.id != "onepagecheckout_positions_position"+k) && (el.value!="")){
			el.value = "";
			alert("This field already exists. Please choose other field !");
			break;
		}
	}
						
}
