var idx=0;
	function goLeft(){
		idx=idx-1;
		if(idx<0) idx=3;
		
		for (var i=0; i<4;i++){
			var temp="img"+i;
			if(i==idx) {
				document.getElementById(temp).style.display="block";
				
				continue;
			} 
			document.getElementById(temp).style.display="none";
		}

	}
	function goRight(){
		idx=idx+1;
		if(idx>3) idx=0;
		
		for (var i=0; i<4;i++){
			var temp="img"+i;
			if(i==idx) {
				document.getElementById(temp).style.display="block";
				continue;
			} 
			document.getElementById(temp).style.display="none";
		}
		
	}