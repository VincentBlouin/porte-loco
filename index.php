<?php
require "predis/src/Autoloader.php";
Predis\Autoloader::register();
try {
    $redis = new Predis\Client([
     'scheme' => 'tcp',
     'host'   => '127.0.0.1',
     'port'   => 6379
    ]);
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
        <v-main>
            <v-container>
                <v-layout row wrap>
                    <v-flex md12>
                    <v-card>
                          <v-card-title>
                              <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                              ></v-text-field>
                            </v-card-title>
                            <v-card-text>
                                <v-data-table
                                        :headers="headers"
                                        :items="dates"
                                        class="elevation-1"
                                        :options="pagination"
                                        :search=search

                                >
                                    <template slot="items" slot-scope="props">
                                        <td>
                                            {{props.item.doorIndex}}
                                        </td>
                                        <td>
                                            {{props.item.formatted}}
                                        </td>
                                    </template>
                                </v-data-table>
                            </v-card-text>
                        </v-card>
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
    let index = 0;
    dates.forEach(function (date) {
        date.formatted = moment(date.date).format('DD MMMM YYYY, HH:mm:ss');
        date.doorIndex = dates.length - index;
        index++;
    });
</script>
<script>
    new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        data: {
            search:'',
            pagination: {
                sortBy: ['time'],
                sortDesc: [true],
                rowsPerPage: -1
            },
            headers: [
                {
                    text: 'Index',
                    value: 'doorIndex'
                },
                {
                    text: 'Jour',
                    value: 'formatted',
                    sort:function(a,b){
                        return b.date.getTime() - a.date.getTime()
                    }
                },
            ],
            dates: dates
        }
    })
</script>
</body>
</html>
