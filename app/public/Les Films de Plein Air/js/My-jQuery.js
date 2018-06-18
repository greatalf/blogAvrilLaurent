
$(document).ready(function(){

	//reservation non-valide

 $('#actionNoSelect').click(function(){
        $('.resaInvalide').fadeToggle('500');
    });

 	//reservation valide

 $('#actionUserConnected').click(function(){
        $('.resaValide').fadeToggle('500');
    });

 	//Infos au survol Films Résa

 $("figure").hover(function(){
		$("figcaption", this).after().toggle("slow");

})

 	//Bouton voir plus

 $(".ReadMore").click(function(){
		$(this).prev().toggle("slow");
	
})

});

