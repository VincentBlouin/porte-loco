const five = require("johnny-five");
const board = new five.Board();
const http = require('http');
const config = require('./config.json');
board.on("ready", function(){
    console.log("Board ready");
    const button = new five.Button(2);
    board.repl.inject({
        button:button
    });
    let nbPorteOuverte = 0;
    button.on("up", function(){
        http.get(config.remoteUrl + "?date=" + new Date(), function(resp){
            resp.on('end', function(){
                console.log('request sent')
            });
        }).on('error', function(){
            console.log('error sending request')
        });
        nbPorteOuverte++;
        console.log("la porte s'est ouverte " + nbPorteOuverte + " fois")
    });
});
console.log("connecting porte-loco")
