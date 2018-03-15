const five = require("johnny-five");
const board = new five.Board();
board.on("ready", function(){
    console.log("Board ready");
    const button = new five.Button(2);
    board.repl.inject({
        button:button
    });
    let nbPorteOuverte = 0;
    button.on("up", function(){
        nbPorteOuverte++;
        console.log("la porte s'est ouverte " + nbPorteOuverte + " fois")
    });
});
console.log("connecting porte-loco")
