const five = require("johnny-five");
const board = new five.Board();
const http = require('http');
board.on("ready", function () {
    console.log("Board ready at " + new Date());
    const button = new five.Button(2);
    board.repl.inject({
        button: button
    });
    let nbPorteOuverte = 0;
    button.on("up", function () {
        http.get({
            host: "127.0.0.1",
            path: encodeURI('/index.php?date=' + new Date())
        }, (resp) => {
            //resp.on('data'... is mandatory for resp.on('end'... to happen
            resp.on("data", function (chunk) {
            });
            resp.on('end', () => {
                console.log('request sent')
            });
        }).on("error", (err) => {
            console.log("Request Error: " + err.message);
        });
        nbPorteOuverte++;
        console.log("la porte s'est ouverte " + nbPorteOuverte + " fois");
    });
});
console.log("connecting porte-loco");
