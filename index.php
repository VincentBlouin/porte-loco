<?php
require "predis/autoload.php";
Predis\Autoloader::register();
try {
    $redis = new Predis\Client();

    // This connection is for a remote server
    /*
        $redis = new PredisClient(array(
            "scheme" => "tcp",
            "host" => "153.202.124.2",
            "port" => 6379
        ));
    */
} catch (Exception $e) {
    header("HTTP/1.1 500 INTERNAL ERROR");
    die($e->getMessage());
}
if (isset($_GET["date"])) {
    $redis->lpush("doorDates", $_GET["date"]);
    header("HTTP/1.1 200 OK");
    die();
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/locale/fr-ca.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app">
    <v-app>
        <v-content>
            <v-container>
                <v-layout row wrap>
                    <v-flex md12>
                        <v-data-table
                                :headers="headers"
                                :items="dates"
                                hide-actions
                                class="elevation-1"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.index}}</td>
                                <td>
                                    {{props.item.time}}
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</div>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
<script>
    var datesStr = <?php echo json_encode($redis->lrange("doorDates", 0, -1))?>;
    var dates = datesStr.map(function (dateStr) {
        return {
            date: new Date(dateStr)
        }
    });
    dates.sort(function (a, b) {
        return a.date.getTime() - b.date.getTime()
    }).forEach(function (date) {
        date.time = moment(date.date).format('DD MMMM YYYY, hh:mm:ss');
    });
</script>
<script>
    new Vue({
        el: '#app',
        data: {
            headers: [
                {
                    text: 'Index',
                    value: ''
                },
                {
                    text: 'Jour',
                    value: 'time'
                },
            ],
            dates: dates
        }
    })
</script>
</body>
</html>
