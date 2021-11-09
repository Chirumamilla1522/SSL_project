window.setInterval(
    function(){
        // document.getElementById('newId').value="moo";
        // document.getElementById('buttonId').click();
        navigator.geolocation.getCurrentPosition(function(position){
            document.getElementById('latitude').value=position.coords.latitude;
            document.getElementById('longitude').value=position.coords.longitude;
            document.getElementById('buttonId').click();
        }, function(position){

        });
}, 10000);

