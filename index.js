var five = require("johnny-five"),
    board = new five.Board(),
    led,
    toggleState = false;
board.on("ready", function(){
  console.log("Board ready");
  // Create a new `button` hardware instance.
 // This example allows the button module to
 // create a completely default instance
 button = new five.Button(2);

 // Inject the `button` hardware into
 // the Repl instance's context;
 // allows direct command line access
 board.repl.inject({
   button: button
 });

 // Button Event API

 // "down" the button is pressed
 // button.on("down", function() {
 //   console.log("down");
 // });

 // "hold" the button is pressed for specified time.
 //        defaults to 500ms (1/2 second)
 //        set
 // button.on("hold", function() {
 //   console.log("hold");
 // });

 // "up" the button is released
    var nbPorteOuverte = 0;
 button.on("down", function() {
     nbPorteOuverte++;
     console.log("la porte s'est ouverte " + nbPorteOuverte + " fois");
 });
});
console.log("connecting");

