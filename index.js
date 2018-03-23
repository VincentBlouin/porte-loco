const five = require("johnny-five");
const board = new five.Board();
const http = require('http');
const config = require('./config.json');
board.on("ready", function () {
    console.log("Board ready");
    const button = new five.Button(2);
    board.repl.inject({
        button: button
    });
    let nbPorteOuverte = 0;
    console.log(config.remoteUrl);
    button.on("up", function () {
        http.get(config.remoteUrl + "?date=" + new Date(), (resp) => {
            resp.on('end', () => {
                console.log('request sent')
            });

        }).on("error", (err) => {
            console.log("Error: " + err.message);
        });
        nbPorteOuverte++;
        console.log("la porte s'est ouverte " + nbPorteOuverte + " fois")
    });
});
console.log("connecting porte-loco")
